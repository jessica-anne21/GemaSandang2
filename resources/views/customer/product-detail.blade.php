@extends('layouts.main')

@section('content')

<div class="container my-5">
    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Bagian Gambar Produk --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 1rem;">
                <img src="{{ asset('storage/' . $product->foto_produk) }}" class="card-img-top img-fluid" alt="{{ $product->nama_produk }}" style="transition: transform 0.3s ease;">
            </div>
        </div>

        {{-- Bagian Detail Informasi --}}
        <div class="col-lg-6">
            <div class="ps-lg-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-decoration-none text-muted">Shop</a></li>
                        <li class="breadcrumb-item active text-truncate" aria-current="page">{{ $product->nama_produk }}</li>
                    </ol>
                </nav>

                <span class="badge mb-2" style="background-color: var(--secondary-color, #C1A77E); color: var(--primary-color, #8D4B55); font-size: 0.9rem;">
                    {{ $product->category->nama_kategori }}
                </span>

                <h1 class="display-5 mb-2" style="font-family: 'Playfair Display', serif; font-weight: 700;">{{ $product->nama_produk }}</h1>
                
                <h2 class="h3 mb-3" style="color: var(--primary-color, #8D4B55); font-weight: 700;">
                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                </h2>

                {{-- Atribut Produk Baru --}}
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @if($product->warna)
                        <span class="badge border text-muted fw-normal p-2 px-3 bg-white" style="border-radius: 2rem;">Warna: {{ ucfirst($product->warna) }}</span>
                    @endif
                    @if($product->style)
                        <span class="badge border text-muted fw-normal p-2 px-3 bg-white" style="border-radius: 2rem;">Style: {{ ucfirst($product->style) }}</span>
                    @endif
                    @if($product->material)
                        <span class="badge border text-muted fw-normal p-2 px-3 bg-white" style="border-radius: 2rem;">Bahan: {{ ucfirst($product->material) }}</span>
                    @endif
                </div>

                <p class="text-muted mb-4">Status: 
                    @if($product->stok > 0)
                        <span class="text-success fw-bold"><i class="bi bi-check2-all"></i> Ready Stock</span>
                    @else
                        <span class="text-danger fw-bold"><i class="bi bi-x-circle"></i> Terjual</span>
                    @endif
                </p>

                <hr class="my-4 opacity-25">

                <h5 style="font-family: 'Playfair Display', serif; font-weight: 600;">Deskripsi Produk</h5>
                <p class="text-secondary" style="white-space: pre-wrap; line-height: 1.7;">{{ $product->deskripsi }}</p>

                <hr class="my-4 opacity-25">

                {{-- Aksi Beli / Tawar --}}
                @auth
                    <div class="d-grid gap-3 mt-4">
                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-custom btn-lg w-100 shadow-sm rounded-pill" {{ $product->stok == 0 ? 'disabled' : '' }}>
                                <i class="bi bi-cart-plus me-2"></i> Tambahkan ke Keranjang
                            </button>
                        </form>

                        @if($product->stok > 0)
                            <button type="button" class="btn btn-outline-dark btn-lg w-100 shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#bargainModal">
                                <i class="bi bi-tags me-2"></i> Tawar Harga
                            </button>
                        @endif
                    </div>

                    {{-- Modal Tawar --}}
                    <div class="modal fade" id="bargainModal" tabindex="-1" aria-labelledby="bargainModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem;">
                                <div class="modal-header border-0 bg-light p-4" style="border-radius: 1.5rem 1.5rem 0 0;">
                                    <h5 class="modal-title" id="bargainModalLabel" style="font-family: 'Playfair Display', serif;">Ajukan Penawaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('bargains.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        
                                        <div class="text-center mb-4">
                                            <p class="text-muted small mb-1">Harga Asli:</p>
                                            <h4 class="fw-bold text-dark">Rp {{ number_format($product->harga, 0, ',', '.') }}</h4>
                                        </div>

                                        <div class="mb-3">
                                            <label for="harga_tawaran" class="form-label small text-muted fw-bold">Harga yang Anda Inginkan</label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text bg-white border-end-0 text-muted">Rp</span>
                                                <input type="number" name="harga_tawaran" id="harga_tawaran" class="form-control border-start-0 ps-0" 
                                                       placeholder="{{ number_format($product->harga * 0.5, 0, ',', '.') }}" 
                                                       min="{{ $product->harga * 0.5 }}" max="{{ $product->harga - 1 }}" required>
                                            </div>
                                            <small class="text-muted mt-2 d-block" style="font-size: 0.8rem;">
                                                *Minimal tawaran 50% dari harga asli.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-custom rounded-pill px-4">Kirim Penawaran</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseguest
                    <a href="{{ route('login') }}" class="btn btn-custom btn-lg w-100 shadow-sm rounded-pill mt-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login untuk Membeli / Menawar
                    </a>
                @endguest
            </div>
        </div>
    </div>

    {{-- REKOMENDASI PRODUK --}}
    @if(isset($recommendations) && $recommendations->count() > 0)
    <div class="mt-5 pt-5 border-top">
        <h3 class="mb-4 text-center" style="font-family: 'Playfair Display', serif;">Mungkin Kamu Juga Suka</h3>
        <div class="row g-4">
            @foreach($recommendations as $rec)
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 shadow-sm product-card" style="border-radius: 1rem; overflow: hidden;">
                        <a href="{{ route('product.show', $rec->id) }}" class="text-decoration-none text-dark">
                            <div style="aspect-ratio: 4/5; overflow: hidden;">
                                <img src="{{ asset('storage/' . $rec->foto_produk) }}" class="w-100 h-100 object-fit-cover" alt="{{ $rec->nama_produk }}">
                            </div>
                            <div class="card-body p-3 text-center">
                                <small class="text-muted text-uppercase mb-1 d-block" style="font-size: 0.7rem; letter-spacing: 1px;">{{ $rec->style }}</small>
                                <h6 class="text-truncate mb-1" style="font-weight: 600;">{{ $rec->nama_produk }}</h6>
                                <p class="mb-0 text-primary" style="font-weight: 700; color: var(--primary-color) !important;">
                                    Rp {{ number_format($rec->harga, 0, ',', '.') }}
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
    :root {
        --primary-color: #8D4B55;
        --secondary-color: #C1A77E;
    }
    .btn-custom {
        background-color: var(--primary-color);
        color: white;
        border: none;
    }
    .btn-custom:hover {
        background-color: #6d3a41;
        color: white;
    }
    .product-card {
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>

@endsection