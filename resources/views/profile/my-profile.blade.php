@extends('layouts.main')

@section('content')
<div class="container-fluid py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="container">
        
        {{-- 1. Notifikasi Sukses --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($user->verification && $user->verification->status == 'rejected')
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 p-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-octagon-fill fs-2 me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Verifikasi KTP Ditolak!</h6>
                        <p class="small mb-2">Alasan: <span class="fw-bold text-decoration-underline">{{ $user->verification->rejection_reason ?? 'Data tidak sesuai.' }}</span></p>
                        <a href="{{ route('verification.form') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">
                            <i class="bi bi-arrow-repeat me-1"></i> Upload Ulang Sekarang
                        </a>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 p-4 mb-5" style="background: white;">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
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
                                @if($user->isVerified())
                                    <i class="bi bi-patch-check-fill text-primary ms-1" style="font-size: 1.5rem;" title="Verified User"></i>
                                @endif
                            </h1>
                            <p class="text-muted mb-3 small">@ {{ $user->username }}</p>
                        </div>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="bi bi-gear-fill me-1"></i> Edit Profile
                        </button>
                    </div>

                    <p class="text-secondary mb-4" style="font-style: italic; max-width: 600px; line-height: 1.6;">
                        "{{ $user->bio ?? 'Belum ada bio nih. Ceritain gaya fashion kamu di sini!' }}"
                    </p>

                    <div class="d-flex gap-3 flex-wrap">
                        {{-- Logika Tombol Berdasarkan Status Verifikasi --}}
                        @if($user->isVerified())
                            <a href="#" class="btn text-white px-4 rounded-pill shadow-sm py-2" style="background-color: #8b6262;">
                                <i class="bi bi-plus-lg me-2"></i> Tambah Item Barter
                            </a>
                        @elseif($user->verification && $user->verification->status == 'pending')
                            <button class="btn btn-warning text-dark px-4 rounded-pill shadow-sm py-2" disabled style="cursor: not-allowed; opacity: 0.8;">
                                <i class="bi bi-clock-history me-2"></i> KTP Sedang Dicek Admin
                            </button>
                        @else
                            <a href="{{ route('verification.form') }}" class="btn btn-danger px-4 rounded-pill shadow-sm py-2">
                                <i class="bi bi-shield-lock-fill me-2"></i> Verifikasi KTP Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
                <div class="d-flex align-items-center gap-3">
                    <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif; color: #8b6262;">Lemari Virtual</h3>
                    <span class="badge rounded-pill px-3 py-2" style="background-color: #8b6262; font-size: 0.8rem;">
                        {{ $userProducts->count() }} Items
                    </span>
                </div>
                <hr style="width: 60px; border: 2px solid #8b6262; opacity: 1;" class="mt-2">
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @forelse($userProducts as $item)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card bg-white">
                        <div class="position-relative">
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
                                <a href="#" class="btn btn-sm btn-link text-decoration-none text-dark fw-bold p-0 text-maroon">Detail <i class="bi bi-arrow-right small"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="py-5 bg-white rounded-4 shadow-sm border border-dashed mx-auto" style="max-width: 500px;">
                        <i class="bi bi-archive text-muted" style="font-size: 3.5rem; opacity: 0.2;"></i>
                        <p class="mt-3 text-muted fw-light px-3">Lemari virtual kamu masih kosong. Mulai upload barang barter kamu!</p>
                        @if(!$user->isVerified())
                            <p class="small text-danger mb-0">
                                <i class="bi bi-info-circle"></i> 
                                @if($user->verification && $user->verification->status == 'pending')
                                    Tunggu verifikasi admin untuk upload ya.
                                @else
                                    Verifikasi KTP dulu ya untuk upload barang.
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('profile.update-full') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header border-0 p-4 bg-light">
                    <h5 class="modal-title fw-bold" id="editProfileModalLabel" style="font-family: 'Playfair Display'; color: #8b6262;">
                        <i class="bi bi-person-lines-fill me-2"></i> Edit Profil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase text-muted">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase text-muted">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">@</span>
                                <input type="text" name="username" class="form-control rounded-3 border-start-0" value="{{ $user->username }}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-uppercase text-muted">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3" value="{{ $user->email }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-uppercase text-muted">Bio</label>
                            <textarea name="bio" class="form-control rounded-3" rows="3" maxlength="160" placeholder="Ceritakan personal style kamu...">{{ $user->bio }}</textarea>
                            <div class="form-text text-end small">Maksimal 160 karakter.</div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="p-3 rounded-3 bg-light mt-2">
                                <label class="form-label small fw-bold text-uppercase text-muted"><i class="bi bi-key me-1"></i> Keamanan</label>
                                <input type="password" name="password" class="form-control rounded-3 mb-2" placeholder="Password Baru">
                                <small class="text-muted d-block">Biarkan kosong jika tidak ingin mengubah password.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white px-5 rounded-pill shadow-sm" style="background-color: #8b6262;">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
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
    .btn-outline-secondary:hover { background-color: #8b6262; border-color: #8b6262; color: white; }
</style>
@endsection