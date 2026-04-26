@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.trends.index') }}" class="btn btn-outline-secondary rounded-circle me-3" style="border: 2px solid #600000; color: #600000;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 style="font-family: 'Playfair Display', serif; color: #600000; font-weight: 800; margin: 0;">Kurasi Tren Fashion</h2>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 1.5rem;">
                <img src="{{ $trend->gambar }}" class="img-fluid" style="width: 100%; height: 450px; object-fit: cover;" id="previewImg" onerror="this.src='https://placehold.co/400x600?text=Image+URL+Error';">
                
                <div class="card-body bg-white text-center">
                    <p class="mb-1 small text-muted text-uppercase fw-bold" style="letter-spacing: 1px;">Data Scraping</p>
                    <span class="badge px-3 py-2" style="background-color: #fdf5f5; color: #600000; border: 1px solid #600000; border-radius: 10px;">
                        Origin: {{ $trend->sumber }}
                    </span>
                    
                    <hr class="my-3 opacity-10">
                    
                    {{-- Link ke Web Asli (Zara/Uniqlo) --}}
                    @if($trend->link_sumber)
                        <a href="{{ $trend->link_sumber }}" target="_blank" class="btn btn-sm py-2 px-3 fw-bold w-100" style="background-color: #600000; color: white; border-radius: 10px; text-decoration: none;">
                            <i class="bi bi-box-arrow-up-right me-2"></i> CEK WEB ASLI
                        </a>
                    @else
                        <span class="text-muted small">Link sumber asli tidak tersedia</span>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- FORM KURASI --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 1.5rem;">
                <form action="{{ route('admin.trends.publish', $trend->id) }}" method="POST">
                    @csrf

                    {{-- JUDUL TREN --}}
                    <div class="mb-4">
                        <label class="fw-bold mb-2" style="color: #600000;">Judul Tren</label>
                        <input type="text" name="judul" class="form-control border-0 bg-light p-3" value="{{ $trend->judul }}" style="border-radius: 12px;" placeholder="Tulis judul yang menarik...">
                    </div>

                    {{-- DESKRIPSI KURASI --}}
                    <div class="mb-4">
                        <label class="fw-bold mb-2" style="color: #600000;">Deskripsi Kurasi</label>
                        <textarea name="deskripsi" class="form-control border-0 bg-light p-3" rows="4" style="border-radius: 12px;" placeholder="Berikan ulasan fashion dari sudut pandang Gema Sandang...">{{ $trend->deskripsi }}</textarea>
                    </div>

                    {{-- EDIT URL GAMBAR & LINK SUMBER (BUAT FIXING HASIL SCRAPING) --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="fw-bold mb-2" style="color: #600000;">URL Gambar</label>
                            <input type="text" name="gambar" id="inputGambar" class="form-control border-0 bg-light p-3" value="{{ $trend->gambar }}" style="border-radius: 12px;" placeholder="Tempel URL gambar baru jika yang lama pecah">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-2" style="color: #600000;">URL Link Sumber</label>
                            <input type="text" name="link_sumber" class="form-control border-0 bg-light p-3" value="{{ $trend->link_sumber }}" style="border-radius: 12px;" placeholder="URL produk di website brand">
                        </div>
                    </div>

                    {{-- METADATA UNTUK REKOMENDASI --}}
                    <div class="row g-3">
                        {{-- STYLE DENGAN HINTS --}}
                        <div class="col-md-4">
                            <label class="fw-bold mb-2" style="color: #600000;">Style / Vibes</label>
                            <input type="text" name="style" list="styleHints" class="form-control border-0 bg-light p-3" value="{{ $trend->style }}" style="border-radius: 12px;" placeholder="e.g. Coquette">
                            <datalist id="styleHints">
                                <option value="Streetwear"><option value="Casual"><option value="Minimalist">
                                <option value="Vintage"><option value="Coquette"><option value="Old Money">
                                <option value="Y2K"><option value="Academia"><option value="Gorpcore">
                            </datalist>
                        </div>

                        {{-- MATERIAL DENGAN HINTS --}}
                        <div class="col-md-4">
                            <label class="fw-bold mb-2" style="color: #600000;">Material</label>
                            <input type="text" name="material" list="materialHints" class="form-control border-0 bg-light p-3" value="{{ $trend->material }}" style="border-radius: 12px;" placeholder="e.g. Linen">
                            <datalist id="materialHints">
                                <option value="Cotton"><option value="Denim"><option value="Silk">
                                <option value="Corduroy"><option value="Linen"><option value="Knit">
                                <option value="Leather"><option value="Chiffon">
                            </datalist>
                        </div>

                        {{-- WARNA --}}
                        <div class="col-md-4">
                            <label class="fw-bold mb-2" style="color: #600000;">Warna Dominan</label>
                            <input type="text" name="warna" class="form-control border-0 bg-light p-3" value="{{ $trend->warna }}" style="border-radius: 12px;" placeholder="e.g. Earth Tone">
                        </div>
                    </div>

                    <hr class="my-4 opacity-10">

                    {{-- ACTION BUTTONS --}}
<div class="d-grid">
    <button type="submit" class="btn btn-lg fw-bold shadow-sm py-3" style="background-color: #600000; color: white; border-radius: 15px; letter-spacing: 1px;">
        @if($trend->status == 'Published')
            <i class="bi bi-pencil-square me-2"></i> PERBARUI DATA TREN
        @else
            <i class="bi bi-megaphone-fill me-2"></i> PUBLIKASIKAN TREN
        @endif
    </button>
    
    <div class="mt-3 p-3 bg-light rounded-3 text-center" style="border: 1px dashed #60000066;">
        <small class="text-muted d-block">
            <i class="bi bi-info-circle me-1"></i>
            @if($trend->status == 'Published')
                Tren ini sudah tayang. Perubahan data <strong>tidak akan</strong> mengirim email ulang ke customer.
            @else
                Aksi ini akan mengubah status menjadi <strong>Published</strong> dan mengirim notifikasi email ke customer.
            @endif
        </small>
    </div>
</div>
</div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('inputGambar').addEventListener('input', function() {
        document.getElementById('previewImg').src = this.value;
    });
</script>

<style>
    .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(96, 0, 0, 0.1) !important;
        border: 1px solid #600000 !important;
    }
</style>
@endsection