<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Bargain;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerBargainController extends Controller
{
public function index()
{
    // 1. Ambil Data Tawaran
    $bargains = Bargain::with('product')
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

    // 2.Fitur Mark as Read
    Bargain::where('user_id', auth()->id())
        ->whereIn('status', ['accepted', 'rejected'])
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return view('customer.bargains.index', compact('bargains'));
}

    /**
     * Menyimpan tawaran baru dari pelanggan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'harga_tawaran' => 'required|numeric|min:1',
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);

        $batasBawah = $product->harga * 0.50; // 50 persen dari harga asli
        
        if ($request->harga_tawaran < $batasBawah) {
            return redirect()->back()
                ->with('error', 'Maaf, tawaran terlalu rendah. Minimal tawaran adalah Rp ' . number_format($batasBawah, 0, ',', '.'));
        }

        // Cek apakah user sudah pernah menawar produk ini dengan status pending
        $existingBargain = \App\Models\Bargain::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('status', 'pending')
            ->first();

        if ($existingBargain) {
            return redirect()->back()->with('error', 'Anda masih memiliki tawaran yang menunggu persetujuan untuk produk ini.');
        }

        // Simpan Tawaran Baru
        \App\Models\Bargain::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'harga_tawaran' => $request->harga_tawaran,
            'status' => 'pending',
        ]);

        return redirect()->route('customer.bargains.index')
            ->with('success', 'Tawaran berhasil dikirim! Tunggu konfirmasi admin ya.');
    }
}