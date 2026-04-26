@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0" style="font-family: 'Playfair Display', serif; color: #800000;">Kelola Kategori Produk</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-custom">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="p-3 ps-4 border-0">ID</th>
                            <th class="p-3 border-0">Nama Kategori</th>
                            <th class="p-3 border-0 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                        <tr>
                            <td class="p-3 ps-4 fw-bold">#{{ $category->id }}</td>
                            <td class="p-3">
                                <div class="fw-bold" style="font-size: 0.9rem;">{{ $category->nama_kategori }}</div>
                            </td>
                            <td class="p-3 text-end pe-4">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-1">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="bi bi-trash3 me-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center p-5 text-muted">
                                <i class="bi bi-tag display-4 d-block mb-3"></i>
                                Belum ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table thead th {
        letter-spacing: 0.05em;
    }
</style>
@endsection