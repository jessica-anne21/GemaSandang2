@extends('layouts.main')

@section('styles')
<style>     
    /* Product Card Koleksi Terbaru */
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease; 
        background-color: #fff;
        border-radius: 1rem;
    }
    .product-card:hover {
        transform: translateY(-8px); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; 
    }
    
    .info-box { border-right: 1px solid #eee; }
    .info-box:last-child { border-right: none; }

    @media (max-width: 768px) {
        .info-box { border-right: none; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px; }
        .info-box:last-child { border-bottom: none; margin-bottom: 0; }
    }

    .sold-out-badge {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        background: rgba(0,0,0,0.7); color: white; padding: 0.5rem 1rem;
        font-weight: bold; text-transform: uppercase; letter-spacing: 2px; width: 100%; text-align: center; z-index: 2;
    }

    .product-sold-out img { opacity: 0.6; filter: grayscale(100%); }

    /* --- STYLE TRENDS MOCKUP (JES'S CHOICE) --- */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .trend-card-mockup {
        background: white; border-radius: 25px; overflow: hidden; 
        box-shadow: 0 15px 35px rgba(0,0,0,0.05); transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .trend-card-mockup:hover { transform: translateY(-12px); box-shadow: 0 20px 40px rgba(139, 98, 98, 0.15); }

    .text-maroon { color: #8b6262 !important; }
    .btn-maroon { background-color: #8b6262; color: white; }
</style>
@endsection

@section('content')

{{-- 1. HERO & TRENDING SECTION (SESUAI MOCKUP GAMBAR) --}}
<div style="background-color: #fdf0f0; padding: 80px 0; overflow: hidden; position: relative;">
    <div class="container">
        <div class="row align-items-center">
            
            {{-- TEKS KIRI --}}
            <div class="col-lg-4 mb-5 mb-lg-0">
                <h1 style="font-family: 'Playfair Display', serif; color: #8b6262; font-weight: 800; font-size: 3.5rem; line-height: 1.1; margin-bottom: 20px;">
                    Temukan Style Impianmu!
                </h1>
                <p style="color: #a07e7e; font-size: 1.2rem; line-height: 1.6; margin-bottom: 35px; max-width: 320px;">
                    Berburu pakaian secondhand berkualitas dengan tren terbaru
                </p>
                <a href="{{ route('trends.index') }}" class="btn px-5 py-3 rounded-pill fw-bold" 
                   style="background-color: #8b6262; color: white; box-shadow: 0 10px 20px rgba(139, 98, 98, 0.2);">
                    Jelajahi Tren Fashion
                </a>
            </div>

            {{-- CONTAINER MAROON KANAN --}}
<div class="col-lg-8">
    <div style="background-color: #8b6262; border-radius: 50px 0 0 50px; padding: 60px 0 60px 50px; margin-right: -300px; position: relative;">
        
        {{-- HORIZONTAL SCROLL CARDS --}}
        {{-- TAMBAHAN: flex-wrap-nowrap biar dia maksa ke samping --}}
        <div class="d-flex flex-nowrap overflow-auto no-scrollbar" style="gap: 30px; padding-right: 300px; padding-bottom: 20px; scroll-behavior: smooth;">
            @forelse($trends as $trend)
                <div style="flex: 0 0 auto; width: 280px;">
                    <div class="trend-card-mockup" style="position: relative;">
                        {{-- IMAGE --}}
                        <div style="height: 350px; background-color: #eee; overflow: hidden; position: relative;">
                            <img src="{{ $trend->gambar }}" style="width: 100%; height: 100%; object-fit: cover;" 
                                 onerror="this.src='https://placehold.co/400x600?text=Fashion+Gema';">
                            <div style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.9); padding: 4px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; color: #8b6262; z-index: 2;">
                                {{ strtoupper($trend->sumber) }}
                            </div>
                        </div>

                        {{-- INFO & AJAX LIKE --}}
                        <div style="padding: 22px; background: white;">
                            <h6 class="fw-bold" style="color: #8b6262; margin-bottom: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $trend->judul }}
                            </h6>
                            
                            <div style="display: flex; gap: 20px; align-items: center; color: #8b6262; font-weight: 800; font-size: 0.9rem;">
                                <span style="cursor: pointer; position: relative; z-index: 3;" class="btn-like" data-id="{{ $trend->id }}">
                                    <i class="bi bi-heart-fill" style="color: #ff5e5e;"></i> 
                                    <span id="score-{{ $trend->id }}">{{ $trend->skor_popularitas }}</span>
                                </span>
                                <span>
                                    <i class="bi bi-chat-dots-fill"></i> {{ count($trend->comments) }}
                                </span>
                            </div>
                            <a href="{{ route('trends.show', $trend->id) }}" class="stretched-link" style="z-index: 1;"></a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-white opacity-50">Belum ada tren terbaru.</p>
            @endforelse
        </div>

    </div>
</div>
        </div>
    </div>
</div>

{{-- 2. INFO PENTING --}}
<div class="container my-5 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex flex-column flex-md-row justify-content-around text-center p-4 border rounded-4 shadow-sm bg-white">
                <div class="info-box px-3 flex-fill">
                    <i class="bi bi-tags-fill text-warning fs-3 d-block mb-2"></i>
                    <h6 class="fw-bold text-uppercase small">Bisa Nego</h6>
                    <small class="text-muted">Item boleh ditawar sesukanya!</small>
                </div>
                <div class="info-box px-3 flex-fill">
                    <i class="bi bi-stars text-primary fs-3 d-block mb-2"></i>
                    <h6 class="fw-bold text-uppercase small">Siap Pakai</h6>
                    <small class="text-muted">Laundry, wangi & higienis.</small>
                </div>
                <div class="info-box px-3 flex-fill">
                    <i class="bi bi-gem text-danger fs-3 d-block mb-2"></i>
                    <h6 class="fw-bold text-uppercase small">Vintage Asli</h6>
                    <small class="text-muted">Kurasi pilihan terbaik.</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3. KOLEKSI TERBARU --}}
<div class="container my-5">
    <div class="row text-center mb-5">
        <div class="col">
            <h2 style="font-family: 'Playfair Display', serif; color: #800000; font-size: 2.5rem; font-weight: 700;">Koleksi Terbaru</h2>
            <p class="text-muted">Temukan gaya unik Anda dari koleksi pilihan kami.</p>
            <hr style="width: 80px; border: 2px solid #800000; opacity: 1; margin: 15px auto;">
        </div>
    </div>

    <div class="row">
        @forelse ($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card {{ $product->stok == 0 ? 'product-sold-out' : '' }}">
                    <div class="position-relative">
                        <a href="{{ route('product.show', $product) }}">
                            <img src="{{ asset('storage/' . $product->foto_produk) }}" class="card-img-top" style="height: 320px; object-fit: cover; border-radius: 1rem 1rem 0 0;">
                        </a>
                        @if($product->stok == 0)
                            <div class="sold-out-badge">SOLD OUT</div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column p-4">
                        <div class="small text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">
                            {{ $product->category->nama_kategori ?? 'Fashion' }}
                        </div>
                        <h6 class="fw-bold text-dark mb-3" style="font-size: 1.1rem;">{{ $product->nama_produk }}</h6>
                        <div class="mt-auto">
                            <h5 class="fw-bold mb-3" style="color: #800000;">
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            </h5>
                            <div class="d-grid gap-2">
                                <a href="{{ route('product.show', $product) }}" class="btn btn-outline-dark btn-sm rounded-pill py-2 fw-bold">Detail</a>
                                @if($product->stok > 0)
                                    @auth
                                        <form action="{{ route('cart.store') }}" method="POST" class="d-grid">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-sm text-white rounded-pill py-2 fw-bold" style="background-color: #800000;">
                                                <i class="bi bi-cart-plus me-1"></i> Beli Sekarang
                                            </button>
                                        </form>
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada produk tersedia.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- SCRIPT AJAX LIKE --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.btn-like').on('click', function(e) {
        e.preventDefault();
        let trendId = $(this).data('id');
        let scoreSpan = $('#score-' + trendId);

        $.ajax({
            url: '/trends/' + trendId + '/like',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    scoreSpan.text(response.new_score);
                    // Tambahin efek kecil biar kerasa interaktif
                    scoreSpan.parent().css('transform', 'scale(1.2)');
                    setTimeout(() => scoreSpan.parent().css('transform', 'scale(1)'), 200);
                }
            },
            error: function(xhr) {
                if(xhr.status === 401) alert('Login dulu yuk, Sis!');
            }
        });
    });
});
</script>

@endsection