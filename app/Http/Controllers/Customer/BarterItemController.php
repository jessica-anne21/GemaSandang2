<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarterItem; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BarterItemController extends Controller
{
    /**
     * Menyimpan barang baru ke lemari virtual
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_barang'   => 'required|string|max:255',
                'deskripsi'     => 'required|string',
                'kategori'      => 'required|string',
                'size'          => 'required|string', // Kolom size wajib diisi
                'kondisi'       => 'required|string',
                'foto_barang'   => 'required|image|mimes:jpeg,png,jpg|max:2048', // Foto Utama
                'foto_lainnya.*'=> 'image|mimes:jpeg,png,jpg|max:2048', // Foto Tambahan
            ]);

            // 1. Upload Foto Utama
            $pathUtama = $request->file('foto_barang')->store('barter_items', 'public');

            // 2. Upload Foto Tambahan (Maksimal 4 tambahan)
            $fotoLainnya = [];
            if ($request->hasFile('foto_lainnya')) {
                foreach ($request->file('foto_lainnya') as $file) {
                    if (count($fotoLainnya) < 4) {
                        $fotoLainnya[] = $file->store('barter_items', 'public');
                    }
                }
            }

            // 3. Simpan ke Database
            BarterItem::create([
                'user_id'     => Auth::id(),
                'nama_barang' => $request->nama_barang,
                'deskripsi'   => $request->deskripsi,
                'kategori'    => $request->kategori,
                'size'        => $request->size, // Simpan ukuran barang
                'kondisi'     => $request->kondisi,
                'foto_barang' => $pathUtama,
                'foto_lainnya'=> json_encode($fotoLainnya), // Simpan array sebagai JSON string
                'status'      => 'available'
            ]);

            return back()->with('success', 'Barang berhasil masuk lemari virtualmu!');

        } catch (\Exception $e) {
            return back()->with('error', 'Waduh, gagal simpan barang: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui detail barang
     */
    public function update(Request $request, $id)
    {
        try {
            $item = BarterItem::where('user_id', Auth::id())->findOrFail($id);

            $request->validate([
                'nama_barang' => 'required|string|max:255',
                'deskripsi'   => 'required',
                'kategori'    => 'required',
                'size'        => 'required',
                'kondisi'     => 'required',
                'foto_barang' => 'nullable|image|max:2048',
            ]);

            // Update data teks
            $item->nama_barang = $request->nama_barang;
            $item->deskripsi   = $request->deskripsi;
            $item->kategori    = $request->kategori;
            $item->size        = $request->size;
            $item->kondisi     = $request->kondisi;

            // Update Foto Utama jika ada upload baru
            if ($request->hasFile('foto_barang')) {
                // Hapus foto lama agar tidak nyampah di storage
                if ($item->foto_barang) {
                    Storage::disk('public')->delete($item->foto_barang);
                }
                $item->foto_barang = $request->file('foto_barang')->store('barter_items', 'public');
            }

            $item->save();

            return back()->with('success', 'Detail barang berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus barang dari lemari
     */
    public function destroy($id)
    {
        try {
            // Pastikan barang yang dihapus adalah milik user yang sedang login
            $item = BarterItem::where('user_id', Auth::id())->findOrFail($id);
            
            // 1. Hapus foto utama
            if ($item->foto_barang) {
                Storage::disk('public')->delete($item->foto_barang);
            }
            
            // 2. Hapus semua foto tambahan dari storage
            if ($item->foto_lainnya) {
                $photos = json_decode($item->foto_lainnya);
                if (is_array($photos)) {
                    foreach ($photos as $photo) {
                        Storage::disk('public')->delete($photo);
                    }
                }
            }

            $item->delete();

            return back()->with('success', 'Barang telah dihapus dari lemarimu.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus barang.');
        }
    }
}