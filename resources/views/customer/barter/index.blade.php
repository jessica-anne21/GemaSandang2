@extends('layouts.main')

@section('content')
<div class="container-fluid py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="container">
        
        {{-- HEADER --}}
        <div class="text-center mb-5">
            <h1 style="font-family: 'Playfair Display', serif; color: #800000; font-weight: 800; font-size: 3rem;">Barter Area</h1>
            <p class="text-muted">Tukar koleksimu dengan gaya baru dari lemari user lain.</p>
            <hr style="width: 100px; border: 2px solid #800000; opacity: 1; margin: 20px auto;">
        </div>

        {{-- ALERT NOTIFIKASI --}}
        @if(session('success'))
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8">
                    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                            <div>
                                <strong class="d-block">Berhasil!</strong>
                                <span class="small">{{ session('success') }}</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        @php
            // Cek status verifikasi user yang sedang login
            $isVerified = auth()->user()->verification && auth()->user()->verification->status == 'verified';
        @endphp

        @if(!$isVerified)
            {{-- TAMPILAN JIKA BELUM VERIFIED --}}
            <div class="row justify-content-center py-5">
                <div class="col-lg-6 text-center">
                    <div class="mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px; border: 3px dashed #800000; background-color: transparent;">
                            <i class="bi bi-shield-lock-fill" style="font-size: 3.5rem; color: #800000;"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-dark" style="font-family: 'Playfair Display';">Akses Barter Terkunci</h2>
                    <p class="text-muted mb-4">
                        Demi keamanan transaksi antar pengguna Gema Sandang, silakan lakukan verifikasi KTP terlebih dahulu untuk membuka akses Barter Area.
                    </p>
                    <a href="{{ route('verification.form') }}" class="btn text-white rounded-pill px-5 py-3 fw-bold shadow-sm" style="background-color: #800000;">
                        Verifikasi Identitas Sekarang
                    </a>
                </div>
            </div>
        @else
            {{-- GRID BARANG BARTER --}}
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @forelse($barterItems as $item)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card bg-white">
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $item->foto_barang) }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                                <span class="position-absolute top-0 end-0 m-2 badge rounded-pill bg-white text-dark shadow-sm px-3 py-2" style="font-size: 0.7rem; opacity: 0.9;">
                                    {{ $item->kategori }}
                                </span>
                            </div>

                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center text-white fw-bold" 
                                         style="width: 30px; height: 30px; background-color: #800000; font-size: 0.7rem;">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                    <div class="small fw-bold text-dark">
                                        {{ $item->user->name }} 
                                        <i class="bi bi-patch-check-fill text-primary ms-1" style="font-size: 0.8rem;"></i>
                                    </div>
                                </div>

                                <h5 class="fw-bold mb-2 text-dark" style="font-size: 1.1rem;">{{ $item->nama_barang }}</h5>
                                <p class="text-muted small mb-4 flex-grow-1">
                                    {{ Str::limit($item->deskripsi, 60) }}
                                </p>

                                <div class="mt-auto d-grid">
                                    <a href="{{ route('barter.show', $item->id) }}" 
                                       class="btn text-white rounded-pill py-2 fw-bold shadow-sm btn-hover" 
                                       style="background-color: #800000;">
                                        Ajukan Barter
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-inbox display-1 text-light"></i>
                        <p class="text-muted mt-3 italic">Belum ada barang yang tersedia untuk dibarter nih.</p>
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
        transition: all 0.3s ease; 
    }
    
    .product-card:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 10px 20px rgba(128, 0, 0, 0.1) !important; 
    }

    .btn-hover:hover {
        background-color: #600000 !important;
        transition: 0.3s;
    }

    .alert {
        border-left: 5px solid #198754 !important; /* Biar ada aksen garis hijau di samping alert */
    }
</style>
@endsection