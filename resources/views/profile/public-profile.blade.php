@extends('layouts.main')

@section('content')
<div class="container-fluid py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="container">
        
        {{-- HEADER PROFIL PUBLIK --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-5" style="background: white;">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    {{-- Avatar --}}
                    <div class="rounded-circle shadow-sm mx-auto d-flex align-items-center justify-content-center" 
                         style="width: 150px; height: 150px; background-color: #8b6262; border: 4px solid #fdf5f5;">
                        <span class="text-white fw-bold" style="font-size: 50px; font-family: 'Playfair Display', serif;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-9 ps-md-5 border-start-md">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <h1 class="h2 fw-bold mb-1" style="font-family: 'Playfair Display', serif; color: #8b6262;">
                                {{ $user->name }}
                                @if($user->verification && $user->verification->status == 'verified')
                                    <i class="bi bi-patch-check-fill text-primary ms-1" style="font-size: 1.5rem;" title="Verified User"></i>
                                @endif
                            </h1>
                            <p class="text-muted mb-2 small">@ {{ $user->username }}</p>
                            
                            {{-- TAMPILAN LOKASI (Kota & Kecamatan saja) --}}
                            <div class="d-flex align-items-center text-secondary small mb-3">
                                <i class="bi bi-geo-alt-fill me-1" style="color: #8b6262;"></i>
                                <span>{{ $user->district ?? 'Kecamatan' }}, {{ $user->city ?? 'Kota' }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-secondary mb-4" style="font-style: italic; max-width: 600px; line-height: 1.6;">
                        {{ $user->bio ?? 'User ini belum menulis bio.' }}
                    </p>

                    <div class="d-flex gap-4 flex-wrap text-muted small">
                        <span><i class="bi bi-calendar3 me-2"></i> Bergabung {{ $user->created_at->format('M Y') }}</span>
                        <span><i class="bi bi-box-seam me-2"></i> {{ $userProducts->count() }} Koleksi</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- LEMARI VIRTUAL SECTION --}}
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex align-items-center gap-3">
                    <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif; color: #8b6262;">Lemari Virtual {{ $user->name }}</h3>
                </div>
                <hr style="width: 60px; border: 2px solid #8b6262; opacity: 1;" class="mt-2">
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @forelse($userProducts as $item)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card bg-white">
                        <div class="position-relative">
                            {{-- Foto Barang Barter --}}
                            <img src="{{ asset('storage/' . $item->foto_barang) }}" class="card-img-top" alt="{{ $item->nama_barang }}" style="height: 220px; object-fit: cover;">
                            <span class="position-absolute top-0 end-0 m-2 badge rounded-pill bg-white text-dark shadow-sm px-3 py-2" style="font-size: 0.7rem; opacity: 0.9;">
                                {{ $item->kategori }}
                            </span>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <h5 class="card-title fw-bold mb-1" style="font-size: 1.1rem; color: #444;">{{ $item->nama_barang }}</h5>
                            <p class="card-text text-muted small mb-3 flex-grow-1">
                                {{ Str::limit($item->deskripsi, 45) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                <span class="small fw-bold text-maroon">{{ $item->kondisi }}</span>
                                
                                {{-- Link ke Detail Barter --}}
                                <a href="{{ url('/barter/'.$item->id) }}" class="btn btn-sm text-decoration-none fw-bold p-0 text-maroon">
                                    Ajak Barter <i class="bi bi-arrow-right small"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="py-5 bg-white rounded-4 shadow-sm border border-dashed mx-auto" style="max-width: 500px;">
                        <i class="bi bi-archive text-muted" style="font-size: 3.5rem; opacity: 0.2;"></i>
                        <p class="mt-3 text-muted fw-light px-3">Sepertinya {{ $user->name }} belum memajang koleksi di lemarinya.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    @media (min-width: 768px) {
        .border-start-md { border-left: 1px solid #eee !important; }
    }
    .text-maroon { color: #8b6262; }
    .product-card { transition: all 0.3s ease; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    .border-dashed { border: 2px dashed #e9ecef !important; }
</style>
@endsection