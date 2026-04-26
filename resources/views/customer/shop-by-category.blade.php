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
        opacity: 0.6;
        filter: grayscale(100%); 
        position: relative; 
    }
    .sold-out-badge {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(141, 75, 85, 0.85); 
        color: white;
        padding: 10px 20px;
        font-weight: bold;
        font-size: 1.2rem;
        text-transform: uppercase;
        z-index: 10;
        border-radius: 5px;
        pointer-events: none; 
    }
    .product-card:hover.product-sold-out {
        transform: translateY(-8px); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        opacity: 0.6; 
        filter: grayscale(100%); 
    }
</style>
@endsection

@section('content')

<div class="container my-5">

    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
             @include('layouts.partials.search-bar')
        </div>
    </div>
    
    <div class="row text-center mb-5"> 
        <div class="col">
            <p class="text-muted mb-2">Menampilkan produk untuk kategori:</p>
            <h1 class="display-4" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
                {{ $category->nama_kategori }}
            </h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @forelse ($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card {{ $product->stok == 0 ? 'product-sold-out' : '' }}">

                    <div class="position-relative">
                        <a href="{{ route('product.show', $product) }}">
                            <img src="{{ asset($product->foto_produk) }}" class="card-img-top" alt="{{ $product->nama_produk }}" style="height: 300px; object-fit: cover;">
                        </a>

                        @if($product->stok == 0)
                            <div class="sold-out-badge">SOLD OUT</div>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">

                        <a href="{{ route('product.show', $product) }}" class="text-decoration-none" style="color: inherit;">
                            <h5 class="card-title" style="font-family: 'Playfair Display', serif;">{{ $product->nama_produk }}</h5>
                        </a>

                        {{-- <p class="card-text text-muted">{{ $product->category->nama_kategori }}</p> --}} 
                        
                        <p class="card-text fw-bold mt-auto fs-5" style="color: var(--primary-color);">
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </p>
                        
                        <div class="mt-3 d-grid gap-2"> 
                            <a href="{{ route('product.show', $product) }}" class="btn btn-outline-dark">Detail</a>
                            
                            @if($product->stok > 0)
                                @auth
                                <form action="{{ route('cart.store') }}" method="POST" class="d-grid">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-custom">
                                        <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                    </button>
                                </form>
                                @elseguest
                                <a href="{{ route('login') }}" class="btn btn-custom">
                                    <i class="bi bi-box-arrow-in-right"></i> Login untuk Beli
                                </a>
                                @endguest
                            @else
                                <button class="btn btn-secondary" disabled>Stok Habis</button>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center py-5">
                    <i class="bi bi-tag display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">Belum ada produk yang tersedia untuk kategori ini.</h4>
                    <p class="mb-0">Coba cek kategori lain atau kembali ke halaman utama.</p>
                </div>
            </div>
        @endforelse 
    </div>
</div>

@endsection