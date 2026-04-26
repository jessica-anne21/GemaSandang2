<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Menampilkan form untuk menambah produk baru.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'warna' => 'required|string',
            'style' => 'required|string',
            'material' => 'required|string',
            'foto_produk' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        // Secara otomatis menyimpan ke folder public/products
        $path = $request->file('foto_produk')->store('', 'public');

        Product::create([
            'nama_produk' => $request->nama_produk,
            'category_id' => $request->category_id,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'warna' => $request->warna,
            'style' => $request->style,
            'material' => $request->material,
            'foto_produk' => $path,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit produk.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Memperbarui produk di database.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        $path = $product->foto_produk; 

        if ($request->hasFile('foto_produk')) {
            // Hapus foto lama agar tidak nyampah di hosting
            if ($product->foto_produk) {
                Storage::disk('public')->delete($product->foto_produk);
            }
            // Upload foto baru
            $path = $request->file('foto_produk')->store('', 'public');
        }

        $product->update([
            'nama_produk' => $request->nama_produk,
            'category_id' => $request->category_id,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'foto_produk' => $path,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy($id)
    {
        $product = Product::withCount('orderItems')->findOrFail($id);

        // Cek apakah produk ini sudah pernah dibeli (Data Integrity)
        if ($product->order_items_count > 0) {
            return redirect()->back()->with('error', 'Produk tidak bisa dihapus karena sudah tersambung dengan riwayat pesanan pelanggan!');
        }

        // Hapus file fisik fotonya juga
        if ($product->foto_produk) {
            Storage::disk('public')->delete($product->foto_produk);
        }

        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}