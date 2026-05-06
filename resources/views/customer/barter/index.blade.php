@extends('layouts.main')

@section('content')
<div class="container-fluid py-5" style="min-height: 100vh;">
    <div class="container">
        
        
        {{-- HEADER --}}
        <div class="text-center mb-5">
            <h1 style="font-family: 'Playfair Display', serif; color: #8b6262; font-weight: 800; font-size: 3rem;">Barter Area</h1>
            <p class="text-muted italic">Tukar koleksimu dengan gaya baru dari lemari user lain.</p>
            <hr style="width: 100px; border: 2px solid #8b6262; opacity: 1; margin: 20px auto;">
            <a href="{{ route('barter.guide') }}" class="btn btn-maroon rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-info-circle me-2"></i> Panduan Barter
            </a>
        </div>

        {{-- ALERT NOTIFIKASI SUKSES --}}
        @if(session('success'))
            <div class="row justify-content-center mb-4">
                <div class="col-lg-7">
                    <div class="alert alert-dismissible fade show border-0 shadow-sm p-3" role="alert" 
                         style="background-color: #fff; border-left: 4px solid #8b6262 !important; border-radius: 12px;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-3" style="color: #8b6262; font-size: 1.2rem;"></i>
                            <span class="text-dark small fw-medium">{{ session('success') }}</span>
                        </div>
                        <button type="button" class="btn-close small" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.7rem; margin-top: 2px;"></button>
                    </div>
                </div>
            </div>
        @endif

        @php
            $isVerified = auth()->user()->verification && auth()->user()->verification->status == 'verified';
        @endphp

        @if(!$isVerified)
            {{-- TAMPILAN JIKA BELUM VERIFIED --}}
            <div class="row justify-content-center py-5">
                <div class="col-lg-6 text-center">
                    <div class="mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" 
                             style="width: 120px; height: 120px; border: 3px dashed #8b6262; background-color: white;">
                            <i class="bi bi-shield-lock-fill" style="font-size: 3.5rem; color: #8b6262;"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-dark" style="font-family: 'Playfair Display';">Akses Barter Terkunci</h2>
                    <p class="text-muted mb-4">Demi keamanan transaksi, silakan verifikasi KTP terlebih dahulu.</p>
                    <a href="{{ route('verification.form') }}" class="btn text-white rounded-pill px-5 py-3 fw-bold shadow-sm" style="background-color: #8b6262;">
                        Verifikasi Sekarang
                    </a>
                </div>
            </div>
        @else
            {{-- GRID BARANG BARTER --}}
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @forelse($barterItems as $item)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card bg-white position-relative">
                            
                            <!-- LINK OVERLAY -->
                            <a href="{{ route('barter.show', $item->id) }}" class="stretched-link"></a>

                            {{-- Image Section --}}
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $item->foto_barang) }}" class="card-img-top" 
                                     style="height: 250px; object-fit: cover;" alt="{{ $item->nama_barang }}">
                                
                                <span class="position-absolute top-0 end-0 m-2 badge rounded-pill bg-white text-dark shadow-sm px-3 py-2" 
                                      style="font-size: 0.7rem; opacity: 0.9; font-weight: 600; z-index: 2;">
                                    {{ $item->kategori }}
                                </span>
                            </div>

                            <div class="card-body p-4 d-flex flex-column">
                                {{-- User & Location Info --}}
                                <div class="d-flex align-items-center mb-3" style="position: relative; z-index: 2;">
                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                         style="width: 35px; height: 35px; background-color: #8b6262; font-size: 0.8rem;">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="small fw-bold text-dark text-truncate" style="max-width: 150px;">
                                            {{ $item->user->name }}
                                        </div>
                                        <div class="text-muted d-flex align-items-center" style="font-size: 0.7rem;">
                                            <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                                            <span class="text-truncate">{{ $item->user->city ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="fw-bold mb-2 text-dark h6 text-truncate">{{ $item->nama_barang }}</h5>
                                <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.4;">
                                    {{ Str::limit($item->deskripsi, 60) }}
                                </p>

                                <div class="mt-auto d-grid" style="position: relative; z-index: 2;">
                                    <button class="btn text-white rounded-pill py-2 fw-bold shadow-sm btn-hover" 
                                            style="background-color: #8b6262;">
                                        Ajukan Barter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted italic">Belum ada barang tersedia.</p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-5">
                {{ $barterItems->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .product-card { 
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
    }
    
    .product-card:hover { 
        transform: translateY(-10px); 
        box-shadow: 0 15px 30px rgba(128, 0, 0, 0.12) !important; 
    }

    .product-card .btn, 
    .product-card .badge, 
    .product-card .align-items-center {
        pointer-events: auto;
    }


    .btn-maroon {
        background-color: #8b6262; /* Warna Merah Maroon Gema Sandang */
        color: white;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-maroon:hover {
        background-color: #8b6262; /* Warna Maroon agak gelap pas di-hover */
        color: white;
        transform: translateY(-2px); /* Efek melayang dikit */
        box-shadow: 0 4px 8px rgba(128, 0, 0, 0.2);
    }
    
    .btn-maroon:active {
        transform: translateY(0);
    }
    .btn-hover:hover {
        background-color: #8b6262 !important;
    }

    .alert {
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection