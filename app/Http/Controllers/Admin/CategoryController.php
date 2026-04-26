<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori.
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menampilkan form untuk menambah kategori baru.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories',
        ]);

        Category::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit kategori.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Memperbarui kategori di database.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori,' . $category->id,
        ]);

        $category->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        // Pengecekan keamanan
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Tidak bisa menghapus kategori ini karena masih memiliki produk terkait.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}