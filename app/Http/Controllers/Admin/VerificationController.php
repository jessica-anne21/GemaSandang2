<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    /**
     * Menampilkan daftar user yang menunggu verifikasi (status pending).
     */
    public function index()
    {
        $verifications = UserVerification::with('user')
                        ->where('status', 'pending')
                        ->latest()
                        ->get();

        return view('admin.verifications.index', compact('verifications'));
    }

    public function approve($id)
    {
        $verif = UserVerification::findOrFail($id);
        
        $verif->update(['status' => 'verified']); 
        
        return back()->with('success', 'User ' . $verif->user->name . ' berhasil diverifikasi!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $verif = UserVerification::findOrFail($id);
        $verif->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);
        
        return back()->with('error', 'Verifikasi ' . $verif->user->name . ' ditolak: ' . $request->reason);
    }

    public function showKtp($id)
    {
        $verification = \App\Models\UserVerification::findOrFail($id);

        return view('admin.verifikasi.show-ktp', compact('verification'));
    }

}