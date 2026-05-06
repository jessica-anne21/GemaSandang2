@extends('layouts.main')

@section('content')
<div class="container-fluid py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="container">
        
        {{-- Logika Penentu Status Verifikasi --}}
        @php
            $isVerified = ($user->verification && $user->verification->status == 'verified');
        @endphp

        {{-- 1. NOTIFIKASI --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 animate-fade-in" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- ALERT JIKA BELUM VERIFIKASI --}}
        @if(!$isVerified)
            <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-3 animate-fade-in">
                <i class="bi bi-shield-exclamation fs-4 me-3 text-warning"></i>
                <div class="flex-grow-1">
                    <strong class="d-block">Fitur Barter Terkunci!</strong>
                    <span class="small">Kamu perlu verifikasi KTP, nomor HP, dan alamat untuk mengelola lemari serta melakukan barter.</span>
                </div>
                <a href="{{ route('verification.form') }}" class="btn btn-warning btn-sm rounded-pill px-4 fw-bold shadow-sm">Verifikasi Sekarang</a>
            </div>
        @endif

        {{-- 2. CARD PROFIL --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-5" style="background: white;">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
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
                                @if($isVerified)
                                    <span class="ms-2 badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill" style="font-size: 0.65rem;">
                                        <i class="bi bi-patch-check-fill me-1"></i> VERIFIED
                                    </span>
                                @endif
                            </div>
                            <p class="text-muted mb-2 small">@ {{ $user->username }}</p>
                            <div class="d-flex align-items-center text-secondary small mb-3">
                                <i class="bi bi-geo-alt-fill me-1" style="color: #800000;"></i>
                                <span>{{ $user->district ?? 'Kecamatan' }}, {{ $user->city ?? 'Kota' }}</span>
                            </div>
                        </div>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                    </div>
                    <p class="text-secondary mb-4 small" style="font-style: italic; max-width: 600px;">"{{ $user->bio ?? 'Belum ada bio.' }}"</p>
                    
                    @if($isVerified)
                        <button class="btn text-white px-4 rounded-pill shadow-sm py-2 fw-bold transition-hover" style="background-color: #800000;" data-bs-toggle="modal" data-bs-target="#addItemModal">
                            <i class="bi bi-plus-lg me-2"></i> Tambah Item Barter
                        </button>
                    @else
                        <button class="btn btn-secondary px-4 rounded-pill shadow-sm py-2 fw-bold opacity-75" disabled style="cursor: not-allowed;">
                            <i class="bi bi-lock-fill me-2"></i> Fitur Terkunci
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
                <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif; color: #800000;">Lemari Virtual</h3>
                <hr style="width: 50px; border: 2px solid #800000; opacity: 1;" class="mt-2">
            </div>
        </div>

        {{-- 3. GRID PRODUK --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 position-relative">
            @if(!$isVerified && $userProducts->count() > 0)
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center rounded-4" 
                     style="background: rgba(253, 245, 245, 0.5); backdrop-filter: blur(5px); z-index: 50; min-height: 400px;">
                    <div class="text-center p-5 bg-white shadow-lg rounded-4 border border-maroon border-opacity-10 animate-fade-in">
                        <i class="bi bi-shield-lock-fill text-maroon fs-1 mb-3 d-block"></i>
                        <h5 class="fw-bold text-dark mb-2">Akses Terbatas</h5>
                        <p class="small text-muted mb-4">Selesaikan verifikasi identitas untuk<br>mengelola item di lemari virtualmu.</p>
                        <a href="{{ route('verification.form') }}" class="btn text-white rounded-pill px-5 fw-bold" style="background-color: #800000;">Verifikasi Sekarang</a>
                    </div>
                </div>
            @endif

            @forelse($userProducts as $item)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card bg-white position-relative {{ $item->status == 'traded' ? 'item-traded' : '' }}" 
                         @if($isVerified && $item->status != 'traded')
                            style="cursor: pointer;" 
                            data-bs-toggle="modal" 
                            data-bs-target="#detailItemModal{{ $item->id }}"
                         @endif
                    >
                        <div class="position-relative img-container">
                            <img src="{{ asset('storage/' . $item->foto_barang) }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                            @if($item->status == 'traded')
                                <div class="traded-overlay">
                                    <span class="badge rounded-pill bg-dark text-white px-3 py-2 shadow-sm">SUCCESSFULLY TRADED</span>
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 m-2 badge rounded-pill bg-white text-dark shadow-sm px-2 py-1" style="font-size: 0.6rem; z-index: 10;">{{ $item->kategori }}</span>
                        </div>
                        <div class="card-body p-3 text-center">
                            <h6 class="fw-bold mb-1 text-truncate">{{ $item->nama_barang }}</h6>
                            <div class="d-flex justify-content-center gap-1 mt-2">
                                <span class="badge bg-light text-dark border" style="font-size: 0.6rem;">Size: {{ $item->size ?? '-' }}</span>
                                <span class="badge {{ $item->status == 'traded' ? 'bg-secondary' : 'bg-soft-maroon text-maroon' }}" style="font-size: 0.6rem;">
                                    {{ $item->status == 'traded' ? 'Traded' : $item->kondisi }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL DETAIL & EDIT ITEM --}}
                @if($item->status != 'traded')
                <div class="modal fade" id="detailItemModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="modal-body p-0">
                                <div class="row g-0">
                                    <div class="col-md-5 bg-dark d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $item->foto_barang) }}" class="w-100" style="height: 500px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-7 p-4">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="badge bg-soft-maroon text-maroon px-3 py-2 rounded-pill">{{ $item->kategori }}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div id="view-mode{{ $item->id }}">
                                            <h3 class="fw-bold mb-3" style="font-family: 'Playfair Display';">{{ $item->nama_barang }}</h3>
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.7rem;">UKURAN</small>
                                                    <span class="fw-bold px-3 py-1 rounded-pill border small d-inline-block">{{ $item->size ?? 'N/A' }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.7rem;">KONDISI</small>
                                                    <span class="fw-bold px-3 py-1 rounded-pill border small d-inline-block">{{ $item->kondisi }}</span>
                                                </div>
                                            </div>
                                            <p class="text-secondary small mb-4">{{ $item->deskripsi }}</p>
                                            <div class="d-flex gap-2">
                                                <button class="btn text-white px-4 rounded-pill shadow-sm py-2 fw-bold" style="background-color: #800000;" onclick="toggleEdit({{ $item->id }})">Edit</button>
                                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-outline-danger rounded-pill"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>

                                        <div id="edit-mode{{ $item->id }}" class="d-none animate-fade-in">
                                            <h5 class="fw-bold text-maroon mb-3 border-bottom pb-2">Edit Item</h5>
                                            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf @method('PATCH')
                                                <input type="text" name="nama_barang" class="form-control mb-2 rounded-3" value="{{ $item->nama_barang }}" required>
                                                <div class="row g-2 mb-2">
                                                    <div class="col-6">
                                                        <select name="kategori" class="form-select rounded-3">
                                                            <option value="Atasan" {{ $item->kategori == 'Atasan' ? 'selected' : '' }}>Atasan</option>
                                                            <option value="Bawahan" {{ $item->kategori == 'Bawahan' ? 'selected' : '' }}>Bawahan</option>
                                                            <option value="Dress" {{ $item->kategori == 'Dress' ? 'selected' : '' }}>Dress</option>
                                                            <option value="Outer" {{ $item->kategori == 'Outer' ? 'selected' : '' }}>Outer</option>
                                                            <option value="Sepatu" {{ $item->kategori == 'Sepatu' ? 'selected' : '' }}>Sepatu</option>
                                                            <option value="Aksesoris" {{ $item->kategori == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <select name="size" class="form-select rounded-3">
                                                            <option value="XS" {{ $item->size == 'XS' ? 'selected' : '' }}>XS</option>
                                                            <option value="S" {{ $item->size == 'S' ? 'selected' : '' }}>S</option>
                                                            <option value="M" {{ $item->size == 'M' ? 'selected' : '' }}>M</option>
                                                            <option value="L" {{ $item->size == 'L' ? 'selected' : '' }}>L</option>
                                                            <option value="XL" {{ $item->size == 'XL' ? 'selected' : '' }}>XL</option>
                                                            <option value="XXL" {{ $item->size == 'XXL' ? 'selected' : '' }}>XXL</option>
                                                            <option value="All Size" {{ $item->size == 'All Size' ? 'selected' : '' }}>All Size</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <select name="kondisi" class="form-select mb-2 rounded-3">
                                                    <option value="Like New" {{ $item->kondisi == 'Like New' ? 'selected' : '' }}>Like New</option>
                                                    <option value="Good Condition" {{ $item->kondisi == 'Good Condition' ? 'selected' : '' }}>Good Condition</option>
                                                    <option value="Well Used" {{ $item->kondisi == 'Well Used' ? 'selected' : '' }}>Well Used</option>
                                                </select>
                                                <textarea name="deskripsi" class="form-control mb-2 rounded-3" rows="3">{{ $item->deskripsi }}</textarea>
                                                <button type="submit" class="btn btn-success rounded-pill px-4">Simpan</button>
                                                <button type="button" onclick="toggleEdit({{ $item->id }})" class="btn btn-light rounded-pill px-4">Batal</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @empty
                <div class="col-12 text-center py-5 text-muted small italic">Lemari kosong.</div>
            @endforelse
        </div>
    </div>
</div>

{{-- MODAL TAMBAH ITEM --}}
<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 bg-light p-4">
                    <h5 class="modal-title fw-bold" style="color: #800000;">Tambah Koleksi Barter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold">NAMA BARANG</label>
                            <input type="text" name="nama_barang" class="form-control rounded-3 shadow-none" placeholder="Zara Blouse..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">KATEGORI</label>
                            <select name="kategori" class="form-select rounded-3 shadow-none">
                                <option>Atasan</option><option>Bawahan</option><option>Dress</option><option>Outer</option><option>Sepatu</option><option>Aksesoris</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">UKURAN (SIZE)</label>
                            <select name="size" class="form-select rounded-3 shadow-none" required>
                                <option value="" selected disabled>Pilih Size</option>
                                <option>XS</option><option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option><option>All Size</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">KONDISI BARANG</label>
                            <select name="kondisi" class="form-select rounded-3 shadow-none" required>
                                <option>Like New</option><option>Good Condition</option><option>Well Used</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">FOTO UTAMA</label>
                            <input type="file" name="foto_barang" class="form-control rounded-3 shadow-none" accept="image/*" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">FOTO DETAIL (MAX 4)</label>
                            <input type="file" name="foto_lainnya[]" class="form-control rounded-3 shadow-none" accept="image/*" multiple onchange="previewImages(this)">
                        </div>
                        <div class="col-12 d-none" id="preview-row">
                            <div id="preview-container" class="d-flex gap-2 flex-wrap p-2 border rounded-3 bg-light"></div>
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold">DESKRIPSI LENGKAP</label>
                            <textarea name="deskripsi" class="form-control rounded-3 shadow-none" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn text-white w-100 rounded-pill py-2 fw-bold shadow-sm" style="background-color: #800000;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT PROFIL --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            {{-- CRITICAL FIX: Pastikan action route bener dan method PATCH ada --}}
            <form action="{{ route('profile.update-full') }}" method="POST">
                @csrf 
                @method('PATCH')
                
                <div class="modal-header border-0 p-4 bg-light">
                    <h5 class="modal-title fw-bold" style="color: #800000;">Update Profil & Alamat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted">NAMA LENGKAP</label>
                        <input type="text" name="name" class="form-control rounded-3 shadow-none" value="{{ $user->name }}" required>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="small fw-bold text-muted">KOTA</label>
                            <input type="text" name="city" class="form-control rounded-3 shadow-none" value="{{ $user->city }}" required>
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold text-muted">KECAMATAN</label>
                            <input type="text" name="district" class="form-control rounded-3 shadow-none" value="{{ $user->district }}" required>
                        </div>
                    </div>
                    
                    {{-- FIX: Gunakan attribute name="nomor_hp" --}}
                    <div class="mb-3">
                        <label class="small fw-bold text-muted">NOMOR HP (WHATSAPP)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 small">+62</span>
                            <input type="number" name="nomor_hp" class="form-control rounded-3 shadow-none" value="{{ $user->nomor_hp }}" required>
                        </div>
                    </div>
                    
                    {{-- FIX: Gunakan attribute name="alamat" --}}
                    <div class="mb-3">
                        <label class="small fw-bold text-muted">ALAMAT LENGKAP PENGIRIMAN</label>
                        <textarea name="alamat" class="form-control rounded-3 shadow-none" rows="3" required>{{ $user->alamat }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="small fw-bold text-muted text-uppercase mb-1">Username</label>
                        <input type="text" name="username" class="form-control rounded-3 shadow-none" value="{{ $user->username }}" required>
                    </div>

                    <div class="mb-0">
                        <label class="small fw-bold text-muted">BIO</label>
                        <textarea name="bio" class="form-control rounded-3 shadow-none" rows="2">{{ $user->bio }}</textarea>
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn text-white w-100 rounded-pill py-2 fw-bold shadow-sm" style="background-color: #800000;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @media (min-width: 768px) { .border-start-md { border-left: 1px solid #eee !important; } }
    .product-card { transition: 0.3s; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(128,0,0,0.1) !important; }
    .item-traded .img-container img { filter: grayscale(100%); opacity: 0.6; }
    .traded-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; z-index: 5; }
    .traded-overlay span { font-size: 0.6rem; letter-spacing: 1px; font-weight: 800; }
    .bg-soft-maroon { background: #fff0f0; }
    .text-maroon { color: #800000; }
    .animate-fade-in { animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    function toggleEdit(id) {
        document.getElementById(`view-mode${id}`).classList.toggle('d-none');
        document.getElementById(`edit-mode${id}`).classList.toggle('d-none');
    }

    function previewImages(input) {
        const container = document.getElementById('preview-container');
        const previewRow = document.getElementById('preview-row');
        container.innerHTML = ''; 
        if (input.files.length > 4) {
            alert("Maksimal 4 foto detail!");
            input.value = "";
            previewRow.classList.add('d-none');
            return;
        }
        if (input.files.length > 0) {
            previewRow.classList.remove('d-none');
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('rounded-2', 'border');
                    img.style.width = '60px'; img.style.height = '60px'; img.style.objectFit = 'cover';
                    container.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        } else {
            previewRow.classList.add('d-none');
        }
    }
</script>
@endsection