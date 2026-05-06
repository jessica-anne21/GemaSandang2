<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BarterItem;
use App\Models\BarterRequest;
use App\Mail\BarterOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BarterController extends Controller
{
    /**
     * Tampilkan semua barang barter yang tersedia (Barter Area)
     */
    public function index()
{
    $userId = auth()->id();

    $barterItems = BarterItem::where('status', 'available')
        ->where('user_id', '!=', $userId) 
        ->whereHas('user.verification', function($query) {
            $query->where('status', 'verified');
        })
        /* 
           LOGIKA PENTING: 
           Jangan tampilkan barang jika ada request yang statusnya 
           pending, accepted, atau on_going yang melibatkan barang ini.
        */
        ->whereDoesntHave('barterRequestsAsRequested', function($query) {
            $query->whereIn('status', ['pending', 'accepted', 'on_going']);
        })
        ->whereDoesntHave('barterRequestsAsOffered', function($query) {
            $query->whereIn('status', ['pending', 'accepted', 'on_going']);
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
        
        $userProducts = BarterItem::where('user_id', Auth::id())
            ->where('status', 'available')
            ->get();

        return view('customer.barter.show', compact('item', 'userProducts'));
    }

    /**
     * Mengirim penawaran barter
     */
    public function sendRequest(Request $request, $id)
    {
        $request->validate([
            'my_item_id' => 'required|exists:barter_items,id',
            'pesan' => 'nullable|string|max:500',
        ]);

        $exists = BarterRequest::where('sender_id', Auth::id())
            ->where('requested_item_id', $id)
            ->whereIn('status', ['pending', 'accepted', 'on_going'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Kamu sudah punya proses barter yang berjalan untuk barang ini!');
        }

        $targetItem = BarterItem::findOrFail($id);

        BarterRequest::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $targetItem->user_id,
            'requested_item_id' => $id,
            'offered_item_id' => $request->my_item_id,
            'message' => $request->pesan,
            'status' => 'pending'
        ]);

        return redirect()->route('barter.index')->with('success', 'Penawaran terkirim! Cek berkala di Inbox ya.');
    }

    /**
     * Riwayat Barter (Inbox)
     */
    public function inbox()
    {
        $userId = Auth::id();

        $incomingRequests = BarterRequest::where('receiver_id', $userId)
            ->with(['sender', 'requestedItem', 'offeredItem'])
            ->latest()
            ->get();

        $myRequests = BarterRequest::where('sender_id', $userId)
            ->with(['receiver', 'requestedItem', 'offeredItem'])
            ->latest()
            ->get();

        return view('customer.barter.inbox', compact('incomingRequests', 'myRequests'));
    }

    /**
     * Halaman Tracking
     */
    public function tracking($id)
    {
        $barter = BarterRequest::with(['requestedItem', 'offeredItem', 'sender', 'receiver'])
            ->where(function($query) {
                $query->where('sender_id', Auth::id())
                      ->orWhere('receiver_id', Auth::id());
            })
            ->findOrFail($id);

        return view('customer.barter.tracking', compact('barter'));
    }

    /**
     * Kirim OTP
     */
    public function sendOtp($id) 
    {
        try {
            $otp = rand(100000, 999999);
            $barter = BarterRequest::findOrFail($id);
            $barter->update(['otp_code' => $otp]);

            Mail::to(Auth::user()->email)->send(new BarterOtpMail($otp, 'Persetujuan Barter Gema Sandang'));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Verifikasi OTP & Terima Barter
     */
    public function verifyAcceptance(Request $request, $id)
    {
        $barter = BarterRequest::findOrFail($id);

        if ($request->otp_input == $barter->otp_code) {
            $barter->update([
                'status' => 'accepted',
                'otp_code' => null 
            ]);
            return back()->with('success', 'Barter disetujui! Silakan pilih metode pengiriman.');
        }

        return back()->with('error', 'Kode OTP salah, Sis!');
    }

    /**
     * Pilih Metode & Set Barang ke PENDING
     */
    public function selectProtection(Request $request, $id)
    {
        $barter = BarterRequest::findOrFail($id);
        $method = $request->method;

        DB::transaction(function () use ($barter, $method) {
            $barter->update([
                'method_selection' => $method,
                'terms_accepted' => true,
                'status' => 'on_going',
                'sender_payment_status' => $method == 'protection' ? 'waiting' : null,
                'receiver_payment_status' => $method == 'protection' ? 'waiting' : null,
            ]);

            // Barang jadi PENDING (tidak bisa ditawar orang lain)
            $barter->offeredItem->update(['status' => 'pending']);
            $barter->requestedItem->update(['status' => 'pending']);
        });

        return back()->with('success', 'Metode ' . ucfirst($method) . ' dipilih. Barang dikunci untuk barter ini.');
    }

    /**
     * Batalkan Barter & Set Barang ke AVAILABLE
     */
    public function cancelBarter($id)
    {
        $barter = BarterRequest::findOrFail($id);

        // Hanya bisa batal kalau belum berstatus completed/rejected
        if (in_array($barter->status, ['completed', 'rejected_qc', 'cancelled'])) {
            return back()->with('error', 'Transaksi ini tidak bisa dibatalkan.');
        }

        DB::transaction(function () use ($barter) {
            // Balikkan barang ke Available
            $barter->offeredItem->update(['status' => 'available']);
            $barter->requestedItem->update(['status' => 'available']);

            // Hapus bukti bayar jika ada (opsional biar hemat storage)
            if ($barter->sender_payment_proof) Storage::disk('public')->delete($barter->sender_payment_proof);
            if ($barter->receiver_payment_proof) Storage::disk('public')->delete($barter->receiver_payment_proof);

            $barter->update(['status' => 'cancelled']);
        });

        return redirect()->route('barter.inbox')->with('success', 'Barter dibatalkan. Barangmu tersedia kembali di lemari.');
    }

    /**
     * Upload Bukti Bayar
     */
    public function uploadPayment(Request $request, $id)
    {
        $request->validate(['payment_proof' => 'required|image|max:2048']);
        $barter = BarterRequest::findOrFail($id);
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        if (Auth::id() == $barter->sender_id) {
            $barter->update(['sender_payment_proof' => $path, 'sender_payment_status' => 'waiting']);
        } else {
            $barter->update(['receiver_payment_proof' => $path, 'receiver_payment_status' => 'waiting']);
        }

        return back()->with('success', 'Bukti terkirim! Menunggu verifikasi admin.');
    }

    /**
     * Update Resi
     */
    public function updateResi(Request $request, $id)
    {
        $barter = BarterRequest::findOrFail($id);
        $request->validate(['resi' => 'required|string|max:100']);

        if (Auth::id() == $barter->sender_id) {
            $barter->update(['sender_resi' => $request->resi]);
        } else {
            $barter->update(['receiver_resi' => $request->resi]);
        }

        return back()->with('success', 'Nomor resi diperbarui!');
    }

    /**
     * Selesaikan Barter & Set Barang ke TRADED
     */
    public function completeBarter($id)
    {
        $barter = BarterRequest::findOrFail($id);
        $userId = Auth::id();

        if ($userId == $barter->sender_id) {
            $barter->sender_confirmed_at = now();
        } else {
            $barter->receiver_confirmed_at = now();
        }
        $barter->save();

        if ($barter->sender_confirmed_at && $barter->receiver_confirmed_at) {
            DB::transaction(function () use ($barter) {
                $barter->update(['status' => 'completed']);
                
                // Final: Barang jadi traded (tidak muncul di index)
                $barter->offeredItem->update(['status' => 'traded']);
                $barter->requestedItem->update(['status' => 'traded']);
            });
        }

        return back()->with('success', 'Konfirmasi diterima!');
    }

    /**
     * Reject Request (Oleh Penerima)
     */
    /**
     * Reject Request (Oleh Penerima)
     */
    public function rejectRequest($id)
    {
        try {
            $barter = BarterRequest::findOrFail($id);
            
            // Keamanan: Pastikan hanya penerima yang bisa menolak
            if ($barter->receiver_id != Auth::id()) {
                return back()->with('error', 'Akses ditolak! Kamu bukan penerima barter ini.');
            }

            // Update status menjadi rejected
            $barter->update([
                'status' => 'rejected'
            ]);

            // Barang tidak perlu diubah statusnya ke available karena 
            // statusnya memang masih available (belum masuk tahap deal/pending)

            return back()->with('success', 'Penawaran barter berhasil ditolak.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak barter: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, $id)
{
    $barter = BarterRequest::findOrFail($id);

    // 1. Validasi Keamanan
    if ($barter->sender_id != auth()->id() && $barter->receiver_id != auth()->id()) {
        return back()->with('error', 'Akses ditolak.');
    }

    // 2. Validasi Status (Hanya bisa batal jika belum selesai/batal/input resi)
    if ($barter->status == 'completed' || $barter->sender_resi || $barter->receiver_resi) {
        return back()->with('error', 'Transaksi tidak bisa dibatalkan karena barang sudah dalam pengiriman.');
    }

    $request->validate([
        'reason' => 'required|string|max:255'
    ]);

    DB::transaction(function () use ($barter, $request) {
        // 3. Update status kedua barang jadi Available kembali
        $barter->offeredItem->update(['status' => 'available']);
        $barter->requestedItem->update(['status' => 'available']);

        // 4. Update status request
        $barter->update([
            'status' => 'cancelled',
            'cancel_reason' => $request->reason,
            'cancelled_by' => auth()->id()
        ]);
    });

    return redirect()->route('barter.inbox')->with('success', 'Barter berhasil dibatalkan.');
}
}