<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BarterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\BarterOtpMail;
use Illuminate\Support\Facades\Mail;
use App\Models\BarterRequest;


class BarterController extends Controller
{
    public function index()
{
    $barterItems = \App\Models\BarterItem::where('status', 'available')
        ->where('user_id', '!=', auth()->id()) 
        ->whereHas('user.verification', function($query) {
            $query->where('status', 'verified');
        })
        ->with(['user.verification']) 
        ->latest()
        ->paginate(12);

    return view('customer.barter.index', compact('barterItems'));
}

    public function show($id)
{
    $item = BarterItem::with('user')->findOrFail($id);
    
    // 1. Ambil barang milik user buat di modal
    $userProducts = BarterItem::where('user_id', auth()->id())
                              ->where('status', 'available')
                              ->get();

    // 2. CEK: Apakah user sudah pernah kirim request untuk barang ini?
    $alreadyRequested = \App\Models\BarterRequest::where('sender_id', auth()->id())
                        ->where('requested_item_id', $id)
                        ->where('status', 'pending')
                        ->exists(); // Hasilnya true atau false

    return view('customer.barter.show', compact('item', 'userProducts', 'alreadyRequested'));
}
    public function sendRequest(Request $request, $id)
{
    $request->validate([
        'my_item_id' => 'required|exists:barter_items,id',
        'pesan' => 'nullable|string|max:500',
        'otp_input' => 'required' 
    ]);

    if ($request->otp_input != session('barter_otp')) {
        return redirect()->back()->with('error', 'Kode OTP salah! Silakan konfirmasi ulang.');
    }

    session()->forget(['barter_otp', 'otp_timestamp']);

    $targetItem = BarterItem::findOrFail($id);

    \App\Models\BarterRequest::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $targetItem->user_id,
        'requested_item_id' => $id,
        'offered_item_id' => $request->my_item_id,
        'message' => $request->pesan,
        'status' => 'pending'
    ]);

    return redirect()->route('barter.index')->with('success', 'Penawaran barter terkirim! Cek statusnya di menu Riwayat Barter ya!');
}

    public function inbox()
{
    // 1. Penawaran yang MASUK (Kita sebagai penerima/receiver)
    $incomingRequests = \App\Models\BarterRequest::where('receiver_id', auth()->id())
        ->with(['sender', 'requestedItem', 'offeredItem'])
        ->latest()
        ->get();

    // 2. Penawaran yang KITA KIRIM (Kita sebagai pengirim/sender)
    $myRequests = \App\Models\BarterRequest::where('sender_id', auth()->id())
        ->with(['receiver', 'requestedItem', 'offeredItem'])
        ->latest()
        ->get();

    return view('customer.barter.inbox', compact('incomingRequests', 'myRequests'));
}

public function updateStatus(Request $request, $id)
{
    $barterReq = \App\Models\BarterRequest::findOrFail($id);

    // Pastikan cuma penerima yang bisa ganti status
    if (auth()->id() !== $barterReq->receiver_id) {
        return redirect()->back()->with('error', 'Eits, bukan barang kamu ini! 🛑');
    }

    $request->validate(['status' => 'required|in:accepted,rejected']);
    
    $barterReq->update(['status' => $request->status]);

    $pesan = $request->status == 'accepted' ? 'Deal! Chat sekarang buat janjian kirim barang.' : 'Yah, penawaran ditolak.';
    
    return redirect()->back()->with('success', $pesan);
}

// Input Resi
public function updateResi(Request $request, $id)
{
    $barter = \App\Models\BarterRequest::findOrFail($id);
    $request->validate(['resi' => 'required|string|max:50']);

    if (auth()->id() == $barter->sender_id) {
        $barter->update(['sender_resi' => $request->resi]);
    } else {
        $barter->update(['receiver_resi' => $request->resi]);
    }

    return redirect()->back()->with('success', 'Nomor resi berhasil diupdate!');
}

// Konfirmasi Barang Sampai
public function confirmArrival($id)
{
    $barter = \App\Models\BarterRequest::findOrFail($id);
    $now = now();

    if (auth()->id() == $barter->sender_id) {
        $barter->update(['sender_received_at' => $now]);
    } else {
        $barter->update(['receiver_received_at' => $now]);
    }

    // Kalau dua-duanya sudah konfirmasi, baru status barang di lemarinya ganti jadi 'traded/unavailable'
    if ($barter->sender_received_at && $barter->receiver_received_at) {
        $barter->requestedItem->update(['status' => 'unavailable']);
        $barter->offeredItem->update(['status' => 'unavailable']);
    }

    return redirect()->back()->with('success', 'Mantap! Transaksi selesai.');
}

public function destroy($id) {
    $item = BarterItem::where('user_id', auth()->id())->findOrFail($id);
    $item->delete();
    return back()->with('success', 'Barang berhasil dikeluarkan dari lemarimu.');
}

public function update(Request $request, $id)
{
    $item = BarterItem::where('user_id', auth()->id())->findOrFail($id);
    
    $request->validate([
        'nama_barang' => 'required|string|max:255',
        'kategori' => 'required',
        'kondisi' => 'required',
        'deskripsi' => 'required',
    ]);

    $item->update($request->all());

    return back()->with('success', 'Detail barang berhasil diperbarui!');
}

// public function sendOtp(Request $request, $id = null)
// {
//     $otp = rand(100000, 999999);
    
//     if ($id) {
//         $barter = BarterRequest::findOrFail($id);
//         $barter->update(['otp_code' => $otp]);
//         $title = "Persetujuan";
//     } else {
//         session(['pending_request_otp' => $otp]);
//         $title = "Pengajuan";
//     }

//     // Kirim via Mailtrap (Konfigurasi di .env)
//     Mail::to(auth()->user()->email)->send(new BarterOtpMail($otp, $title));

//     return response()->json(['success' => true]);
// }

public function sendOtp($id = null) 
{
    try {
        $otp = rand(100000, 999999);
        
        // Simpan ke Session saja dulu karena data BarterRequest-nya belum dibuat
        session(['barter_otp' => $otp]);
        session(['otp_timestamp' => now()]);

        // Kirim email ke si Penawar (auth user)
        Mail::to(auth()->user()->email)->send(new BarterOtpMail($otp, 'Pengajuan Barter'));

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function verifyAcceptance(Request $request, $id)
{
    $barter = BarterRequest::findOrFail($id);

    if ($request->otp_input == $barter->otp_code) {
        $barter->update([
            'status' => 'accepted',
            'otp_code' => null
        ]);
        return back()->with('success', 'Barter disetujui! Chat sudah terbuka.');
    }

    return back()->with('error', 'Aduh, kode OTP-nya salah nih!');
}

}