<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBarterController extends Controller
{
    /**
     * Menampilkan semua daftar transaksi barter (Monitoring)
     */
    public function index()
    {
        // Mengambil semua request barter dengan relasi yang dibutuhkan
        $barters = BarterRequest::with(['sender', 'receiver', 'requestedItem', 'offeredItem'])
            ->latest()
            ->paginate(10);

        return view('admin.barter.index', compact('barters'));
    }

    /**
     * Verifikasi Pembayaran Trade Protection (Approve)
     */
    public function verifyPayment($id, $userType)
    {
        $barter = BarterRequest::findOrFail($id);
        
        // Tentukan kolom status berdasarkan tipe user (sender/receiver)
        $column = ($userType == 'sender') ? 'sender_payment_status' : 'receiver_payment_status';
        
        $barter->update([
            $column => 'paid'
        ]);

        return back()->with('success', "Pembayaran " . ucfirst($userType) . " untuk Barter #{$id} telah diverifikasi.");
    }

    /**
     * Menolak Pembayaran Trade Protection (Reject)
     */
    public function rejectPayment($id, $userType)
    {
        $barter = BarterRequest::findOrFail($id);
        
        // Tentukan kolom mana yang akan diproses
        $proofColumn = ($userType == 'sender') ? 'sender_payment_proof' : 'receiver_payment_proof';
        $statusColumn = ($userType == 'sender') ? 'sender_payment_status' : 'receiver_payment_status';

        // 1. Hapus file fisik dari storage agar tidak memenuhi server
        if ($barter->$proofColumn) {
            Storage::disk('public')->delete($barter->$proofColumn);
        }

        // 2. Reset data di database agar user bisa upload ulang
        $barter->update([
            $proofColumn => null,
            $statusColumn => 'waiting'
        ]);

        return back()->with('error', "Bukti transfer " . ucfirst($userType) . " ditolak. User dipersilakan upload ulang.");
    }

    /**
     * Detail Barter (Jika admin butuh lihat lebih lengkap di halaman terpisah)
     */
    public function show($id)
    {
        $barter = BarterRequest::with(['sender', 'receiver', 'requestedItem', 'offeredItem'])
            ->findOrFail($id);

        return view('admin.barter.show', compact('barter'));
    }

    public function updateLogistic(Request $request, $id, $userType)
{
    $barter = BarterRequest::findOrFail($id);
    
    if ($userType == 'sender') {
        $barter->update([
            'sender_logistic_status' => $request->logistic_status,
            'resi_from_admin_to_receiver' => $request->admin_resi, // Barang sender dikirim ke receiver
        ]);
    } else {
        $barter->update([
            'receiver_logistic_status' => $request->logistic_status,
            'resi_from_admin_to_sender' => $request->admin_resi, // Barang receiver dikirim ke sender
        ]);
    }

    return back()->with('success', "Status logistik " . ucfirst($userType) . " berhasil diperbarui!");
}

public function rejectQC(Request $request, $id) {
        $barter = BarterRequest::findOrFail($id);
        $rejectedUser = $request->rejected_user_id; // ID User yang barangnya zonk

        DB::transaction(function () use ($barter, $rejectedUser, $request) {
            // 1. Tandai transaksi gagal QC
            $barter->update([
                'status' => 'rejected_qc',
                'admin_note' => $request->reason // Alasan misal: "Baju robek di ketiak"
            ]);

            // 2. Balikkan status barang ke Available lagi (biar mereka bisa perbaiki deskripsi)
            $barter->offeredItem->update(['status' => 'available']);
            $barter->requestedItem->update(['status' => 'available']);
            
            // 3. Logika Refund Biaya Layanan
            if ($rejectedUser == $barter->sender_id) {
                // Sender salah -> Receiver (Korban) dapet status refund
                $barter->update(['receiver_payment_status' => 'refunded']);
            } else {
                // Receiver salah -> Sender (Korban) dapet status refund
                $barter->update(['sender_payment_status' => 'refunded']);
            }
        });

        return back()->with('success', 'QC ditolak. Instruksi pengembalian barang dikirim ke user.');
    }
}