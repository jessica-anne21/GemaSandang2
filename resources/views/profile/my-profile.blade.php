@extends('layouts.main')

@section('content')
<div class="container-fluid py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="container">
        
        {{-- 1. NOTIFIKASI FLASH --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- 2. ALERT VERIFIKASI DITOLAK --}}
        @if($user->verification && $user->verification->status == 'rejected')
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 p-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-octagon-fill fs-2 me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Verifikasi KTP Ditolak</h6>
                        <p class="small mb-2">Alasan: <span class="fw-bold text-decoration-underline">{{ $user->verification->rejection_reason ?? 'Data tidak sesuai standar.' }}</span></p>
                        <a href="{{ route('verification.form') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">
                            Upload Ulang Sekarang
                        </a>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- 3. CARD PROFIL UTAMA --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-5" style="background: white;">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <div class="rounded-circle shadow-sm mx-auto d-flex align-items-center justify-content-center" 
                         style="width: 150px; height: 150px; background-color: #800000; border: 4px solid #fdf5f5;">
                        <span class="text-white fw-bold" style="font-size: 50px; font-family: 'Playfair Display', serif;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-9 ps-md-5 border-start-md">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <h1 class="h2 fw-bold mb-1" style="font-family: 'Playfair Display', serif; color: #800000;">
                                {{ $user->name }}
                                @if($user->verification && $user->verification->status == 'verified')
                                    <i class="bi bi-patch-check-fill text-primary ms-1" style="font-size: 1.5rem;" title="Verified User"></i>
                                @endif
                            </h1>
                            <p class="text-muted mb-2 small">@ {{ $user->username }}</p>
                            
                            {{-- TAMPILAN LOKASI --}}
                            <div class="d-flex align-items-center text-secondary small mb-3">
                                <i class="bi bi-geo-alt-fill me-1" style="color: #800000;"></i>
                                <span>{{ $user->district ?? 'Kecamatan' }}, {{ $user->city ?? 'Kota belum diatur' }}</span>
                            </div>
                        </div>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="bi bi-gear-fill me-1"></i> Edit Profile
                        </button>
                    </div>

                    <p class="text-secondary mb-4" style="font-style: italic; max-width: 600px; line-height: 1.6;">
                        {{ $user->bio ?? 'Belum ada bio.' }}
                    </p>

                    <div class="d-flex gap-3 flex-wrap">
                        @if($user->verification && $user->verification->status == 'verified')
                            <button class="btn text-white px-4 rounded-pill shadow-sm py-2" style="background-color: #800000;" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                <i class="bi bi-plus-lg me-2"></i> Tambah Item Barter
                            </button>
                        @elseif($user->verification && $user->verification->status == 'pending')
                            <button class="btn btn-warning text-dark px-4 rounded-pill shadow-sm py-2 fw-bold" disabled>
                                <i class="bi bi-clock-history me-2"></i> KTP Sedang Dicek Admin
                            </button>
                        @else
                            <a href="{{ route('verification.form') }}" class="btn btn-danger px-4 rounded-pill shadow-sm py-2 fw-bold">
                                <i class="bi bi-shield-lock-fill me-2"></i> Verifikasi KTP Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. JUDUL LEMARI VIRTUAL --}}
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex align-items-center gap-3">
                    <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif; color: #800000;">Lemari Virtual</h3>
                    @if($user->verification && $user->verification->status == 'verified')
                        <span class="badge rounded-pill px-3 py-2" style="background-color: #800000; font-size: 0.8rem;">
                            {{ $userProducts->count() }} Items
                        </span>
                    @endif
                </div>
                <hr style="width: 60px; border: 2px solid #800000; opacity: 1;" class="mt-2">
            </div>
        </div>

        {{-- 5. GRID PRODUK --}}
        @if($user->verification && $user->verification->status == 'verified')
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @forelse($userProducts as $item)
                    <div class="col">
                        {{-- Isi Card Produk (Sama seperti code kamu sebelumnya) --}}
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card bg-white">
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $item->foto_barang) }}" class="card-img-top {{ $item->status !== 'available' ? 'filter-grayscale opacity-75' : '' }}" alt="{{ $item->nama_barang }}" style="height: 220px; object-fit: cover;">
                                <span class="position-absolute top-0 end-0 m-2 badge rounded-pill bg-white text-dark shadow-sm px-3 py-2" style="font-size: 0.7rem;">{{ $item->kategori }}</span>
                            </div>
                            <div class="card-body p-3">
                                <h5 class="card-title fw-bold mb-1" style="font-size: 1.1rem;">{{ $item->nama_barang }}</h5>
                                <p class="card-text text-muted small mb-3">{{ Str::limit($item->deskripsi, 45) }}</p>
                                <button type="button" class="btn btn-sm btn-link text-decoration-none fw-bold p-0 text-maroon" data-bs-toggle="modal" data-bs-target="#detailItemModal{{ $item->id }}">
                                    Detail <i class="bi bi-arrow-right small"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Empty State --}}
                @endforelse
            </div>
        @else
            {{-- TAMPILAN LOCK --}}
            <div class="row justify-content-center py-5">
                <div class="col-lg-6 text-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 120px; height: 120px; border: 3px dashed #800000;">
                        <i class="bi bi-shield-lock-fill" style="font-size: 3.5rem; color: #800000;"></i>
                    </div>
                    <h4 class="fw-bold">Lemari Virtual Terkunci</h4>
                    <p class="text-muted">Verifikasi KTP kamu untuk mengisi lemari virtual.</p>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL EDIT PROFIL (LOKASI DITAMBAHKAN DI SINI) --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('profile.update-full') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header border-0 p-4 bg-light">
                    <h5 class="modal-title fw-bold" style="color: #800000;">Edit Profil & Lokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name }}" required>
                    </div>
                    
                    {{-- BARIS LOKASI BARU --}}
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Kota / Kabupaten</label>
                            <input type="text" name="city" class="form-control rounded-3" value="{{ $user->city }}" placeholder="Contoh: Bandung" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Kecamatan</label>
                            <input type="text" name="district" class="form-control rounded-3" value="{{ $user->district }}" placeholder="Contoh: Coblong" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Username</label>
                        <input type="text" name="username" class="form-control rounded-3" value="{{ $user->username }}" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted text-uppercase">Bio</label>
                        <textarea name="bio" class="form-control rounded-3" rows="3">{{ $user->bio }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 text-center">
                    <button type="submit" class="btn text-white px-5 rounded-pill w-100 py-2" style="background-color: #800000;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- CSS KHUSUS --}}
<style>
    @media (min-width: 768px) { .border-start-md { border-left: 1px solid #eee !important; } }
    .product-card:hover { transform: translateY(-8px); }
    .text-maroon { color: #800000 !important; }
    .bg-soft-maroon { background: #fdf5f5; }
</style>
@endsection