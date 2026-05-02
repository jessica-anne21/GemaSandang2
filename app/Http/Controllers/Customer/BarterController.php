<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BarterItem;
use App\Models\BarterRequest;
use App\Mail\BarterOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class BarterController extends Controller
{
    /**
     * Tampilkan semua barang barter yang tersedia
     */
    public function index()
    {
        $barterItems = BarterItem::where('status', 'available')
            ->where('user_id', '!=', auth()->id()) 
            ->whereHas('user.verification', function($query) {
                $query->where('status', 'verified');
            })
            ->with(['user']) 
            ->latest()
            ->paginate(12);

        return view('customer.barter.index', compact('barterItems'));
    }

    /**
     * Detail barang barter
     */
    public function show($id)
    {
        $item = BarterItem::with('user')->findOrFail($id);
        
        // Ambil barang milik user yang sedang login untuk pilihan barter
        $userProducts = BarterItem::where('user_id', auth()->id())
                                  ->where('status', 'available')
                                  ->get();

        return view('customer.barter.show', compact('item', 'userProducts'));
    }

    /**
     * Mengirim penawaran barter (TANPA OTP sesuai request)
     */
    public function sendRequest(Request $request, $id)
    {
        $request->validate([
            'my_item_id' => 'required|exists:barter_items,id',
            'pesan' => 'nullable|string|max:500',
        ]);

        $targetItem = BarterItem::findOrFail($id);

        // Cek apakah sudah pernah mengajukan penawaran untuk barang ini
        $exists = BarterRequest::where('sender_id', auth()->id())
                                ->where('requested_item_id', $id)
                                ->where('status', 'pending')
                                ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Kamu sudah punya penawaran pending untuk barang ini!');
        }

        BarterRequest::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $targetItem->user_id,
            'requested_item_id' => $id,
            'offered_item_id' => $request->my_item_id,
            'message' => $request->pesan,
            'status' => 'pending'
        ]);

        return redirect()->route('barter.index')->with('success', 'Penawaran barter terkirim! Pantau terus di Riwayat Barter ya.');
    }

    /**
     * Daftar Riwayat Barter (Inbox)
     */
    public function inbox()
    {
        $userId = auth()->id();

        // Penawaran yang MASUK ke kita
        $incomingRequests = BarterRequest::where('receiver_id', $userId)
            ->with(['sender', 'requestedItem', 'offeredItem'])
            ->latest()
            ->get();

        // Penawaran yang KITA KIRIM ke orang lain
        $myRequests = BarterRequest::where('sender_id', $userId)
            ->with(['receiver', 'requestedItem', 'offeredItem'])
            ->latest()
            ->get();

        return view('customer.barter.inbox', compact('incomingRequests', 'myRequests'));
    }

    /**
     * Kirim OTP untuk si Penerima (saat mau ACC)
     */
    public function sendOtp($id = null) 
    {
        try {
            $otp = rand(100000, 999999);
            
            if ($id) {
                // Jika ada ID, berarti OTP untuk proses Accept (disimpan di DB)
                $barter = BarterRequest::findOrFail($id);
                $barter->update(['otp_code' => $otp]);
                $subject = 'Persetujuan Barter';
            } else {
                // Fallback (opsional)
                session(['barter_otp' => $otp]);
                $subject = 'Verifikasi Gema Sandang';
            }

            Mail::to(auth()->user()->email)->send(new BarterOtpMail($otp, $subject));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Verifikasi OTP untuk Menyetujui Barter (Untuk Receiver)
     */
    public function verifyAcceptance(Request $request, $id)
    {
        $barter = BarterRequest::findOrFail($id);

        if ($request->otp_input == $barter->otp_code) {
            $barter->update([
                'status' => 'accepted',
                'otp_code' => null // Hapus OTP setelah sukses
            ]);
            return back()->with('success', 'Barter disetujui! Silakan lanjut berdiskusi di menu Chat.');
        }

        return back()->with('error', 'Kode OTP salah! Periksa email kamu lagi ya.');
    }

    /**
     * Reject Penawaran Barter
     */
    public function rejectRequest($id)
    {
        $barterReq = BarterRequest::findOrFail($id);

        if (auth()->id() !== $barterReq->receiver_id) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $barterReq->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Penawaran barter berhasil ditolak.');
    }

    /**
     * Update Resi (Setelah barang dikirim sendiri-sendiri)
     */
    public function updateResi(Request $request, $id)
    {
        $barter = BarterRequest::findOrFail($id);
        $request->validate(['resi' => 'required|string|max:50']);

        if (auth()->id() == $barter->sender_id) {
            $barter->update(['sender_resi' => $request->resi]);
        } else {
            $barter->update(['receiver_resi' => $request->resi]);
        }

        return redirect()->back()->with('success', 'Nomor resi berhasil diperbarui!');
    }

    /**
     * Konfirmasi Barang Sampai (Logic Opsi 3)
     */
    public function confirmArrival($id)
    {
        $barter = BarterRequest::findOrFail($id);
        $now = now();

        if (auth()->id() == $barter->sender_id) {
            $barter->update(['sender_received_at' => $now]);
        } else {
            $barter->update(['receiver_received_at' => $now]);
        }

        // Skenario: Jika kedua belah pihak sudah terima barang
        if ($barter->sender_received_at && $barter->receiver_received_at) {
            // Update status barang jadi unavailable (sudah laku ter-barter)
            $barter->requestedItem->update(['status' => 'unavailable']);
            $barter->offeredItem->update(['status' => 'unavailable']);
            
            // Di sini kamu bisa tambahin logic refund deposit jika ada
        }

        return redirect()->back()->with('success', 'Konfirmasi penerimaan berhasil disimpan.');
    }

    /**
     * Edit Barang (Loker)
     */
    public function update(Request $request, $id)
    {
        $item = BarterItem::where('user_id', auth()->id())->findOrFail($id);
        
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required',
            'kondisi' => 'required',
            'deskripsi' => 'required',
        ]);

        $item->update($validated);

        return back()->with('success', 'Detail barang di lemarimu sudah diperbarui!');
    }

    /**
     * Hapus Barang dari Loker
     */
    public function destroy($id) {
        $item = BarterItem::where('user_id', auth()->id())->findOrFail($id);
        $item->delete();
        return back()->with('success', 'Barang sudah dikeluarkan dari lemari virtualmu.');
    }
}