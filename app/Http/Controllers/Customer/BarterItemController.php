<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarterItem; 
use Illuminate\Support\Facades\Storage;

class BarterItemController extends Controller
{
    public function store(Request $request)
{
    try {
        // 1. Validasi Input (Cek apakah ada yang kurang)
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi'   => 'required',
            'kategori'    => 'required',
            'kondisi'     => 'required',
            'foto_barang' => 'required|image|max:2048', 
        ]);

        // 2. Upload Foto
        if ($request->hasFile('foto_barang')) {
            $path = $request->file('foto_barang')->store('barter_items', 'public');
        } else {
            throw new \Exception("File foto nggak kebaca nih, Sis!");
        }

        // 3. Eksekusi Simpan (Try-Catch bakal nangkep error di sini)
        $item = \App\Models\BarterItem::create([
            'user_id'     => auth()->id(),
            'nama_barang' => $request->nama_barang,
            'deskripsi'   => $request->deskripsi,
            'kategori'    => $request->kategori,
            'kondisi'     => $request->kondisi,
            'foto_barang' => $path,
            'status'      => 'available'
        ]);

        return back()->with('success', 'Barang berhasil masuk lemari virtual kamu!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Kalau error gara-gara input nggak sesuai validasi
        dd($e->errors());
        
    } catch (\Exception $e) {
        // Kalau error gara-gara database (misal: kolom kurang, typo nama tabel, dll)
        dd([
            'Pesan Error' => $e->getMessage(),
            'Line' => $e->getLine(),
            'File' => $e->getFile()
        ]);
    }
}
}