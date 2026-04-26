@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0" style="font-family: 'Playfair Display', serif; color: #800000;">Kelola Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-custom">
            <i class="bi bi-plus-circle me-1"></i> Tambah Produk
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4 alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 1rem; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-uppercase small fw-bold text-muted">
                        <th class="p-3 ps-4 border-0">ID</th>
                        <th class="p-3 ps-4">Produk</th>
                        <th class="p-3 text-center">Kategori</th>
                        <th class="p-3 text-center">Harga</th>
                        <th class="p-3 text-center">Stok</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="p-3 ps-4 fw-bold">#{{ $product->id }}</td>
                            <td class="p-3 ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $product->foto_produk) }}" 
                                         class="rounded-3 shadow-sm border me-3" 
                                         style="width: 55px; height: 55px; object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $product->nama_produk }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 text-center">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                    {{ $product->category->nama_kategori ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td class="p-3 text-center fw-bold text-dark">
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-center">
                                @if($product->stok <= 0)
                                    <span class="badge bg-danger rounded-pill px-3">Habis</span>
                                @else
                                    <span class="badge bg-light text-dark border rounded-pill px-3">{{ $product->stok }} pcs</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="btn btn-sm btn-outline-primary rounded-circle p-2" 
                                       title="Edit Produk">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Sistem akan mengecek riwayat transaksi terlebih dahulu.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Hapus Produk">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-5">
                                <img src="https://illustrations.popsy.co/gray/empty-box.svg" style="width: 150px;" class="mb-3 opacity-50">
                                <p class="text-muted italic">Belum ada produk di etalase Gema Sandang.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="card-footer bg-white border-0 py-4 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection