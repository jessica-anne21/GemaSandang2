<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bargain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;


class AdminBargainController extends Controller
{
    /**
     * Menampilkan daftar tawaran masuk.
     */
    public function index()
    {
        $bargains = Bargain::with(['user', 'product'])->latest()->paginate(10);
        return view('admin.bargains.index', compact('bargains'));
    }

    /**
     * Memproses tawaran (Terima/Tolak).
     */
    public function update(Request $request, $id)
    {
        $bargain = Bargain::findOrFail($id);
    
        if ($bargain->status !== 'pending') {
            return back()->with('error', 'Tawaran ini sudah diproses dan tidak dapat diubah kembali.');
        }
    
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'catatan_admin' => 'nullable|string|max:255',
        ]);
    
        $bargain->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);
    
        return back()->with('success', 'Status tawaran berhasil diperbarui.');
    }

    public function reject(Request $request, $id)
    {
        $bargain = Bargain::findOrFail($id);
        
        $request->validate([
            'catatan_admin' => 'required|string|max:255'
        ]);

        $bargain->update([
            'status' => 'rejected',
            'catatan_admin' => $request->catatan_admin 
        ]);

        return redirect()->back()->with('success', 'Tawaran ditolak dan catatan telah dikirim ke customer.');
    }

}