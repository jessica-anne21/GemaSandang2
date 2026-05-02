<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    /**
     * Menampilkan daftar user yang menunggu verifikasi (status pending).
     */
    public function index()
    {
        // Mengambil verifikasi yang statusnya pending beserta data usernya
        $verifications = UserVerification::with('user')
                        ->where('status', 'pending')
                        ->latest()
                        ->get();

        return view('admin.verifications.index', compact('verifications'));
    }

    /**
     * Menyetujui verifikasi KTP dan Selfie.
     */
    public function approve($id)
    {
        // Pakai DB Transaction biar aman, kalau satu gagal, semua batal
        DB::transaction(function () use ($id) {
            $verif = UserVerification::findOrFail($id);
            
            // 1. Update status di tabel verifikasi
            $verif->update([
                'status' => 'verified',
                'verified_at' => now(),
                'rejection_reason' => null // Reset alasan jika sebelumnya pernah ditolak
            ]);

            // 2. Jika kamu punya kolom is_verified di tabel users, aktifkan ini:
            // $verif->user->update(['is_verified' => true]);
        });

        $verif = UserVerification::find($id);
        return back()->with('success', 'Identitas ' . $verif->user->name . ' telah diverifikasi secara resmi.');
    }

    /**
     * Menolak verifikasi dengan alasan tertentu.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $verif = UserVerification::findOrFail($id);
        
        $verif->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'verified_at' => null
        ]);
        
        return back()->with('success', 'Verifikasi ' . $verif->user->name . ' telah ditolak dengan alasan: ' . $request->reason);
    }

    /**
     * Melihat detail dokumen secara full (opsional jika modal kurang besar).
     */
    public function show($id)
    {
        $verification = UserVerification::with('user')->findOrFail($id);
        return view('admin.verifications.show', compact('verification'));
    }
}