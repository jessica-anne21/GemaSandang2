@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold" style="font-family: 'Playfair Display'; color: #8b6262;">Verifikasi Identitas</h3>
                    <p class="text-muted small">Halo {{ auth()->user()->name }}, lengkapi data di bawah untuk membuka fitur Barter Gema Sandang.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 small">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success border-0 small">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('verification.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Input NIK -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Nomor NIK (16 Digit)</label>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                               value="{{ old('nik') }}" placeholder="Contoh: 3201..." maxlength="16" required>
                    </div>

                    <div class="row">
                        <!-- Upload KTP -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold">Foto KTP Saja</label>
                            <div class="upload-box p-3 border rounded-3 text-center bg-light">
                                <i class="bi bi-card-image d-block fs-2 mb-2" style="color: #8b6262;"></i>
                                <input type="file" name="ktp_image" class="form-control form-control-sm" accept="image/*" required>
                            </div>
                            <div class="form-text x-small mt-2">Pastikan seluruh data di KTP terbaca jelas.</div>
                        </div>

                        <!-- Upload Selfie + KTP (Fitur Anti-Canva) -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold">Selfie Pegang KTP</label>
                            <div class="upload-box p-3 border rounded-3 text-center bg-light">
                                <i class="bi bi-person-bounding-box d-block fs-2 mb-2" style="color: #8b6262;"></i>
                                <input type="file" name="selfie_image" class="form-control form-control-sm" accept="image/*" required>
                            </div>
                            <div class="form-text x-small mt-2">Wajah dan KTP harus terlihat dalam satu frame.</div>
                        </div>
                    </div>

                    <!-- Alert Edukasi Keamanan -->
                    <div class="alert alert-warning border-0 small d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                        <div>
                            Sistem kami melakukan verifikasi manual. Pastikan pencahayaan terang dan foto tidak goyang (blur) untuk mempercepat proses persetujuan.
                        </div>
                    </div>

                    <button type="submit" class="btn text-white w-100 rounded-pill py-2 fw-bold shadow-sm" 
                            style="background-color: #8b6262; transition: 0.3s;">
                        Kirim untuk Verifikasi
                    </button>
                    
                    <a href="{{ url()->previous() }}" class="btn btn-link w-100 text-decoration-none mt-2 small" style="color: #8b6262;">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .upload-box {
        border-style: dashed !important;
        border-color: #dee2e6 !important;
        transition: 0.3s;
    }
    .upload-box:hover {
        border-color: #8b6262 !important;
        background-color: #fff !important;
    }
    .x-small {
        font-size: 0.75rem;
        line-height: 1.2;
    }
    .form-control:focus {
        border-color: #8b6262;
        box-shadow: 0 0 0 0.25rem rgba(139, 98, 98, 0.25);
    }
</style>
@endsection