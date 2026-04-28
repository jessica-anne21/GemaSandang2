@extends('layouts.admin')

@section('content')
<div class="container py-5" style="background-color: #fdfafb; min-height: 100vh;">
    {{-- HEADER --}}
    <div class="d-flex align-items-center mb-5">
        <a href="{{ route('admin.trends.index') }}" class="btn btn-outline-dark rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border: 2px solid #600000; color: #600000;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="mb-0" style="font-family: 'Playfair Display', serif; color: #600000; font-weight: 900;">Kurasi Tren Fashion</h2>
            <p class="text-muted small text-uppercase ls-2 mb-0" style="letter-spacing: 2px;">Gema Sandang Editor Mode</p>
        </div>
    </div>
    
    <div class="row g-4">
        {{-- PREVIEW CARD (KIRI) --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm overflow-hidden sticky-top" style="border-radius: 2rem; top: 20px; z-index: 10;">
                <div class="position-relative">
                    <img src="{{ $trend->gambar }}" class="img-fluid" style="width: 100%; height: 500px; object-fit: cover;" id="previewImg" onerror="this.src='https://placehold.co/400x600?text=Image+URL+Error';">
                    <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                        <span class="badge rounded-pill px-3 py-2 mb-2" style="background-color: #600000; border: 1px solid rgba(255,255,255,0.3);">
                            Origin: {{ $trend->sumber }}
                        </span>
                    </div>
                </div>
                
                <div class="card-body bg-white text-center p-4">
                    <p class="mb-2 small text-muted text-uppercase fw-bold ls-1">Data Scraping Source</p>
                    @if($trend->link_sumber)
                        <a href="{{ $trend->link_sumber }}" target="_blank" class="btn btn-sm py-2 px-3 fw-bold w-100" style="background-color: #600000; color: white; border-radius: 12px; text-decoration: none;">
                            <i class="bi bi-box-arrow-up-right me-2"></i> CEK WEB ASLI
                        </a>
                    @else
                        <span class="text-muted small italic">Link sumber asli tidak tersedia</span>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- FORM KURASI (KANAN) --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 2rem;">
                <form action="{{ route('admin.trends.publish', $trend->id) }}" method="POST">
                    @csrf

                    {{-- JUDUL & DESKRIPSI --}}
                    <div class="mb-4">
                        <label class="fw-bold mb-2 luxury-label">Judul Tren</label>
                        <input type="text" name="judul" class="form-control luxury-input" value="{{ $trend->judul }}" placeholder="Tulis judul yang menarik..." required>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold mb-2 luxury-label">Deskripsi Kurasi</label>
                        <textarea name="deskripsi" class="form-control luxury-input" rows="4" placeholder="Berikan ulasan fashion dari sudut pandang Gema Sandang...">{{ $trend->deskripsi }}</textarea>
                    </div>

                    {{-- URL ASSETS --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="fw-bold mb-2 luxury-label">URL Gambar</label>
                            <input type="text" name="gambar" id="inputGambar" class="form-control luxury-input" value="{{ $trend->gambar }}" placeholder="Tempel URL gambar baru...">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-2 luxury-label">URL Link Sumber</label>
                            <input type="text" name="link_sumber" class="form-control luxury-input" value="{{ $trend->link_sumber }}" placeholder="URL produk asli...">
                        </div>
                    </div>

                    {{-- METADATA --}}
                    <div class="row g-3 mb-5">
                        <div class="col-md-4">
                            <label class="fw-bold mb-2 luxury-label">Style / Vibes</label>
                            <input type="text" name="style" list="styleHints" class="form-control luxury-input" value="{{ $trend->style }}" placeholder="e.g. Coquette">
                            <datalist id="styleHints">
                                <option value="Streetwear"><option value="Casual"><option value="Minimalist">
                                <option value="Vintage"><option value="Coquette"><option value="Old Money">
                            </datalist>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold mb-2 luxury-label">Material</label>
                            <input type="text" name="material" list="materialHints" class="form-control luxury-input" value="{{ $trend->material }}" placeholder="e.g. Linen">
                            <datalist id="materialHints">
                                <option value="Cotton"><option value="Denim"><option value="Silk"><option value="Linen">
                            </datalist>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold mb-2 luxury-label">Warna Dominan</label>
                            <input type="text" name="warna" class="form-control luxury-input" value="{{ $trend->warna }}" placeholder="e.g. Earth Tone">
                        </div>
                    </div>

                    <hr class="my-5 opacity-10">

                    {{-- BAGIAN NOTIFIKASI --}}
                    <div class="mb-5 p-4 rounded-4 shadow-sm" style="background-color: #fdf5f5; border: 1px solid rgba(96,0,0,0.1);">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <label class="fw-bold d-block mb-1" style="color: #600000; font-size: 1rem; cursor: pointer;" for="sendEmailNotif">
                                    Kirim Blast Email ke Customer
                                </label>
                                <small class="text-muted d-block">
                                    Aktifkan jika tren ini ingin dijadikan highlight utama di email user.
                                </small>
                            </div>
                            <div class="form-check form-switch ms-3">
                                <input class="form-check-input custom-switch" type="checkbox" name="send_email_notif" id="sendEmailNotif" role="switch">
                            </div>
                        </div>
                    </div>

                    {{-- SUBMIT BUTTON --}}
                    <div class="d-grid gap-3">
                        <button type="submit" class="btn btn-lg fw-bold shadow-sm py-3 btn-main-action">
                            @if($trend->status == 'Published')
                                <i class="bi bi-pencil-square me-2"></i> PERBARUI DATA TREN
                            @else
                                <i class="bi bi-megaphone-fill me-2"></i> PUBLIKASIKAN TREN
                            @endif
                        </button>
                        
                        <div class="p-3 bg-light rounded-3 text-center border">
                            <small class="text-muted d-block">
                                <i class="bi bi-info-circle me-1 text-dark"></i>
                                @if($trend->status == 'Published')
                                    Tren sudah tayang. Perubahan data <strong>tidak akan</strong> mengirim email ulang.
                                @else
                                    Status akan menjadi <strong>Published</strong> dan notifikasi akan dikirim jika switch aktif.
                                @endif
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .luxury-label { color: #600000; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .luxury-input {
        border: 0 !important;
        background-color: #f8f9fa !important;
        padding: 0.8rem 1.2rem !important;
        border-radius: 12px !important;
        transition: 0.3s;
    }

    .luxury-input:focus {
        background-color: #fff !important;
        box-shadow: 0 10px 20px rgba(96, 0, 0, 0.05) !important;
        border: 1px solid #600000 !important;
    }

    .btn-main-action {
        background-color: #600000;
        color: white;
        border-radius: 15px;
        letter-spacing: 1px;
        transition: 0.3s;
    }

    .btn-main-action:hover {
        background-color: #450000;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(96, 0, 0, 0.2);
    }

    /* Styling Switch agar Proporsional */
    .custom-switch {
        width: 3.2em !important;
        height: 1.6em !important;
        cursor: pointer;
        margin-top: 0;
        float: none;
    }

    .custom-switch:checked {
        background-color: #600000 !important;
        border-color: #600000 !important;
    }

    .form-check.form-switch {
        padding-left: 0;
        margin-bottom: 0;
    }

    .ls-1 { letter-spacing: 1px; }
    .ls-2 { letter-spacing: 2px; }
</style>

<script>
    document.getElementById('inputGambar').addEventListener('input', function() {
        document.getElementById('previewImg').src = this.value;
    });
</script>
@endsection