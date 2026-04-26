<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Bargain;
use App\Models\Cart; 

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index()
    {
        // Ambil data dari tabel carts berdasarkan user yang login
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $removedItems = [];

        foreach ($cartItems as $item) {
            // Cek apakah produknya masih ada atau stoknya habis (dibeli orang lain)
            if (!$item->product || $item->product->stok < 1) {
                $removedItems[] = $item->product ? $item->product->nama_produk : 'Produk Tidak Diketahui'; 
                $item->delete(); // Hapus otomatis dari tabel carts
            }
        }

        if (!empty($removedItems)) {
            $namaProduk = implode(', ', $removedItems);
            session()->flash('warning', "Maaf, produk ($namaProduk) baru saja di-checkout pelanggan lain. Stok thrift kami terbatas 1 pcs per item.");
            return redirect()->route('cart.index'); 
        }

        return view('customer.cart', compact('cartItems')); 
    }
    
    /**
     * Menyimpan produk baru ke dalam keranjang (Normal Price).
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        $existingCart = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingCart) {
            return redirect()->back()->with('warning', 'Produk "' . $product->nama_produk . '" sudah ada di keranjang kamu.');
        }

        // Cek stok awal
        if ($product->stok < 1) {
            return redirect()->back()->with('error', 'Maaf, stok produk habis.');
        }
        
        // Simpan ke tabel carts
        Cart::create([
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
            'kuantitas'   => 1,
            'harga'      => $product->harga, 
            'is_bargain' => false,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menyimpan produk hasil nego ke keranjang.
     */
    public function addFromBargain(Request $request)
    {
        $bargain = Bargain::with('product')
            ->where('id', $request->bargain_id)
            ->where('user_id', auth()->id())
            ->where('status', 'accepted')
            ->firstOrFail();

        // Cek apakah hasil nego ini sudah dimasukkan ke keranjang sebelumnya
        $existingCart = Cart::where('user_id', auth()->id())
            ->where('product_id', $bargain->product_id)
            ->first();

        if ($existingCart) {
            // Jika sudah ada, update harganya jadi harga nego
            $existingCart->update([
                'harga'      => $bargain->harga_tawaran,
                'is_bargain' => true,
                'bargain_id' => $bargain->id
            ]);
        } else {
            // Jika belum ada, buat baru di tabel carts
            Cart::create([
                'user_id'    => auth()->id(),
                'product_id' => $bargain->product_id,
                'kuantitas'   => 1,
                'harga'      => $bargain->harga_tawaran, 
                'is_bargain' => true, 
                'bargain_id' => $bargain->id,
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Produk hasil tawar berhasil dimasukkan ke keranjang dengan harga spesial!');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function remove($id)
    {
        // Cari di tabel carts berdasarkan ID baris keranjangnya
        $cartItem = Cart::where('user_id', auth()->id())->where('id', $id)->first();

        if($cartItem) {
            $cartItem->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}