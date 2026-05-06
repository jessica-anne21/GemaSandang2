@extends('layouts.main')

@section('content')
<div class="container-fluid py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="container">
        
        {{-- HEADER PROFIL PUBLIK --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-5" style="background: white;">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    {{-- Avatar dengan inisial --}}
                    <div class="rounded-circle shadow-sm mx-auto d-flex align-items-center justify-content-center" 
                         style="width: 140px; height: 140px; background-color: #800000; border: 4px solid #fdf5f5;">
                        <span class="text-white fw-bold" style="font-size: 45px; font-family: 'Playfair Display', serif;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-9 ps-md-5 border-start-md">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <h1 class="h2 fw-bold mb-0" style="font-family: 'Playfair Display', serif; color: #800000;">{{ $user->name }}</h1>
                                @if($user->verification && $user->verification->status == 'verified')
                                    <span class="ms-2 badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill" style="font-size: 0.65rem;">
                                        <i class="bi bi-patch-check-fill me-1"></i> VERIFIED
                                    </span>
                                @endif
                            </div>
                            <p class="text-muted mb-2 small">@ {{ $user->username }}</p>
                            
                            {{-- TAMPILAN LOKASI --}}
                            <div class="d-flex align-items-center text-secondary small mb-3">
                                <i class="bi bi-geo-alt-fill me-1" style="color: #800000;"></i>
                                <span>{{ $user->district ?? 'Kecamatan' }}, {{ $user->city ?? 'Kota' }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-secondary mb-4 small" style="font-style: italic; max-width: 600px; line-height: 1.6;">
                        "{{ $user->bio ?? 'User ini belum menulis bio.' }}"
                    </p>

                    <div class="d-flex gap-4 flex-wrap text-muted" style="font-size: 0.75rem;">
                        <span><i class="bi bi-calendar3 me-2"></i> Bergabung {{ $user->created_at->format('M Y') }}</span>
                        <span><i class="bi bi-box-seam me-2"></i> {{ $userProducts->count() }} Koleksi Lemari</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
                <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif; color: #800000;">Lemari Virtual {{ explode(' ', $user->name)[0] }}</h3>
                <hr style="width: 50px; border: 2px solid #800000; opacity: 1;" class="mt-2">
            </div>
        </div>

        {{-- LEMARI VIRTUAL GRID --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @forelse($userProducts as $item)
                <div class="col">
                    {{-- Link Barter (Hanya jika belum traded) --}}
                    @if($item->status != 'traded')
                        <a href="{{ url('/barter/'.$item->id) }}" class="text-decoration-none">
                    @endif

                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card bg-white position-relative {{ $item->status == 'traded' ? 'item-traded' : '' }}">
                        
                        <div class="position-relative img-container">
                            {{-- FOTO --}}
                            <img src="{{ asset('storage/' . $item->foto_barang) }}" 
                                 class="card-img-top" 
                                 alt="{{ $item->nama_barang }}"
                                 style="height: 250px; object-fit: cover;">

                            {{-- BADGE TRADED --}}
                            @if($item->status == 'traded')
                                <div class="traded-overlay">
                                    <span class="badge rounded-pill bg-dark text-white px-3 py-2 shadow-sm">SUCCESSFULLY TRADED</span>
                                </div>
                            @endif

                            {{-- KATEGORI --}}
                            <span class="position-absolute top-0 end-0 m-2 badge rounded-pill bg-white text-dark shadow-sm px-3 py-2" style="font-size: 0.6rem; opacity: 0.9; z-index: 10;">
                                {{ $item->kategori }}
                            </span>
                        </div>

                        <div class="card-body p-3 text-center">
                            <h6 class="fw-bold mb-1 text-truncate text-dark">{{ $item->nama_barang }}</h6>
                            
                            {{-- Info Size & Kondisi (Persis My Profile) --}}
                            <div class="d-flex justify-content-center gap-1 mt-2">
                                <span class="badge bg-light text-dark border" style="font-size: 0.6rem;">Size: {{ $item->size ?? '-' }}</span>
                                <span class="badge {{ $item->status == 'traded' ? 'bg-secondary' : 'bg-soft-maroon text-maroon' }}" style="font-size: 0.6rem;">
                                    {{ $item->status == 'traded' ? 'Traded' : $item->kondisi }}
                                </span>
                            </div>

                            {{-- CTA --}}
                            <div class="mt-3 pt-2 border-top">
                                @if($item->status != 'traded')
                                    <span class="text-maroon fw-bold small transition-hover">
                                        Ajak Barter <i class="bi bi-arrow-right ms-1"></i>
                                    </span>
                                @else
                                    <span class="text-muted small fw-bold">Sudah Tidak Tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($item->status != 'traded')
                        </a>
                    @endif
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="py-5 bg-white rounded-4 shadow-sm border border-dashed mx-auto" style="max-width: 500px;">
                        <i class="bi bi-archive text-muted" style="font-size: 3.5rem; opacity: 0.2;"></i>
                        <p class="mt-3 text-muted small px-3">
                            Sepertinya {{ $user->name }} belum memajang koleksi di lemarinya.
                        </p>
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
    
    /* Warna Maroon Konsisten */
    .text-maroon { color: #800000 !important; }
    .bg-soft-maroon { background: #fff0f0; }
    
    /* Design Card Persis My Profile */
    .product-card { transition: all 0.3s ease; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 10px 25px rgba(128,0,0,0.1) !important; }
    
    /* Grayscale Traded Logic */
    .item-traded .img-container img { filter: grayscale(100%); opacity: 0.6; }
    .item-traded h6 { color: #999; }
    .traded-overlay { 
        position: absolute; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        z-index: 5; 
        background: rgba(0,0,0,0.1);
    }
    .traded-overlay span { font-size: 0.6rem; letter-spacing: 1px; font-weight: 800; }
    
    .border-dashed { border: 2px dashed #e9ecef !important; }
    .animate-fade-in { animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    .transition-hover:hover { letter-spacing: 0.5px; transition: 0.3s; }
</style>
@endsection