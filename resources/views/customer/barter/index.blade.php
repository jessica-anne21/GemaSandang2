@extends('layouts.main')

@section('content')
<div class="container-fluid py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="container">
        
        {{-- HEADER --}}
        <div class="text-center mb-5">
            <h1 style="font-family: 'Playfair Display', serif; color: #8b6262; font-weight: 800; font-size: 3rem;">Barter Area</h1>
            <p class="text-muted">Tukar koleksimu dengan gaya baru dari lemari user lain.</p>
            <hr style="width: 100px; border: 2px solid #8b6262; opacity: 1; margin: 20px auto;">
        </div>

        {{-- GRID BARANG BARTER --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @forelse($barterItems as $item)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card bg-white">
                        <div class="position-relative">
                            {{-- Foto Barang --}}
                            <img src="{{ asset('storage/' . $item->foto_barang) }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                            
                            {{-- Badge Kategori --}}
                            <span class="position-absolute top-0 end-0 m-2 badge rounded-pill bg-white text-dark shadow-sm px-3 py-2" style="font-size: 0.7rem; opacity: 0.9;">
                                {{ $item->kategori }}
                            </span>
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            {{-- Info Pemilik --}}
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center text-white fw-bold" 
                                     style="width: 30px; height: 30px; background-color: #8b6262; font-size: 0.7rem;">
                                    {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                </div>
                                <a href="{{ url('/profile/' . $item->user->id) }}" class="small text-decoration-none fw-bold text-dark">
                                    {{ $item->user->name }} 
                                    <i class="bi bi-patch-check-fill text-primary ms-1" style="font-size: 0.8rem;"></i>
                                </a>
                            </div>

                            <h5 class="fw-bold mb-2" style="color: #444;">{{ $item->nama_barang }}</h5>
                            <p class="text-muted small mb-4 flex-grow-1">
                                {{ Str::limit($item->deskripsi, 60) }}
                            </p>

                            <div class="mt-auto d-grid">
                                <a href="{{ route('barter.show', $item->id) }}" class="btn text-white rounded-pill py-2 fw-bold" style="background-color: #8b6262;">
                                    Ajukan Barter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted mt-3 italic">Belum ada barang yang tersedia untuk dibarter nih, Sis.</p>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-center mt-5">
            {{ $barterItems->links() }}
        </div>
    </div>
</div>
@endsection