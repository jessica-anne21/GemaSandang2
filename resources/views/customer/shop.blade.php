@extends('layouts.main')

@section('styles')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .product-sold-out {
        opacity: 0.7;
        filter: grayscale(100%);
        position: relative;
    }
    .sold-out-badge {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 10px 20px;
        font-weight: bold;
        text-transform: uppercase;
        z-index: 10;
        border-radius: 5px;
        pointer-events: none;
    }

    .sold-out-divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin-top: 3rem;
        margin-bottom: 2rem;
    }
    .sold-out-divider::before,
    .sold-out-divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #ddd;
    }
    .sold-out-divider:not(:empty)::before {
        margin-right: 1.5em;
    }
    .sold-out-divider:not(:empty)::after {
        margin-left: 1.5em;
    }
</style>
@endsection

@section('content')

<div class="container my-5">
    
    <div class="row justify-content-center mb-5">
        <div class="col-md-6">
             @include('layouts.partials.search-bar')
        </div>
    </div>

    <div class="row text-center mb-5">
        <div class="col">
            @if(isset($query) && $query != null)
                <p class="text-muted mb-2">Hasil Pencarian:</p>
                <h2 style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
                    "{{ $query }}"
                </h2>
                <p class="text-muted mt-2">Ditemukan {{ $products->count() }} produk</p>
                <a href="{{ route('shop') }}" class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="bi bi-x-circle"></i> Reset Pencarian
                </a>
            @else
                <h1 class="display-4" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
                    Koleksi Kami
                </h1>
                <p class="lead text-muted">Temukan gaya unik Anda dari koleksi pilihan kami.</p>
            @endif
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach (['success', 'warning', 'error'] as $msg)
                @if(session($msg))
                    <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show shadow-sm" role="alert">
                        {{ session($msg) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="row">
        @forelse ($products as $index => $product)
            
            @if($product->stok == 0 && ($loop->first || $products[$index - 1]->stok > 0))
                <div class="col-12">
                    <div class="sold-out-divider">
                        <span class="text-muted fw-bold small" style="letter-spacing: 2px;">
                            PRODUK HABIS TERJUAL
                        </span>
                    </div>
                </div>
            @endif

            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card {{ $product->stok == 0 ? 'product-sold-out' : '' }}">

                    <div class="position-relative">
                        <a href="{{ route('product.show', $product) }}">
                            <img src="{{ asset('storage/' . $product->foto_produk) }}" class="card-img-top" alt="{{ $product->nama_produk }}" style="height: 300px; object-fit: cover;">
                        </a>

                        @if($product->stok == 0)
                            <div class="sold-out-badge">SOLD OUT</div>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column p-3">
                        <div class="small text-muted mb-1">{{ $product->category->nama_kategori }}</div>
                        
                        <a href="{{ route('product.show', $product) }}" class="text-decoration-none text-dark">
                            <h5 class="card-title" style="font-family: 'Playfair Display', serif;">{{ $product->nama_produk }}</h5>
                        </a>
                        
                        <div class="mt-auto pt-2">
                            <h6 class="fw-bold mb-0" style="color: var(--primary-color);">
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            </h6>
                        </div>
                        
                        <div class="mt-3 d-grid gap-2">
                            <a href="{{ route('product.show', $product) }}" class="btn btn-outline-dark btn-sm rounded-0">Detail</a>
                            
                            @if($product->stok > 0)
                                @auth
                                    <form action="{{ route('cart.store') }}" method="POST" class="d-grid">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-custom btn-sm">
                                            <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                        </button>
                                    </form>
                                @elseguest
                                    <a href="{{ route('login') }}" class="btn btn-custom btn-sm">
                                        <i class="bi bi-box-arrow-in-right"></i> Login untuk Beli
                                    </a>
                                @endguest
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Stok Habis</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light text-center py-5">
                    <i class="bi bi-search display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">Produk tidak ditemukan.</h4>
                    <p class="text-muted">Coba kata kunci lain atau kembali lagi nanti.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection