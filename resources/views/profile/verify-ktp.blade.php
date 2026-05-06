@extends('layouts.main')

@section('content')
<div class="container py-5" style="min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold" style="font-family: 'Playfair Display'; color: #8b6262;">Verifikasi & Aktivasi Fitur</h3>
                    <p class="text-muted small">Halo {{ auth()->user()->name }}, lengkapi data identitas dan pengiriman untuk mulai barter.</p>
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

                <form action="{{ route('verification.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- Identitas Dasar -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Nomor NIK (16 Digit)</label>
                            <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                                   value="{{ old('nik') }}" placeholder="3201..." maxlength="16" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Nomor HP (WhatsApp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">+62</span>
                                <input type="number" name="nomor_hp" class="form-control @error('nomor_hp') is-invalid @enderror" 
                                       value="{{ old('nomor_hp') }}" placeholder="812xxx" required>
                            </div>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="col-12 mb-4">
                            <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Alamat Lengkap Pengiriman</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                      rows="3" placeholder="Nama Jalan, No. Rumah, RT/RW, Kecamatan, dsb..." required>{{ old('alamat') }}</textarea>
                            <div class="form-text x-small">Mohon isi alamat dengan detail agar barang barter tidak salah kirim.</div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Upload KTP -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Foto KTP</label>
                            <div class="upload-box p-3 border rounded-3 text-center bg-light">
                                <i class="bi bi-card-image d-block fs-2 mb-2" style="color: #8b6262;"></i>
                                <input type="file" name="ktp_image" class="form-control form-control-sm" accept="image/*" required>
                            </div>
                        </div>

                        <!-- Upload Selfie -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Selfie Pegang KTP</label>
                            <div class="upload-box p-3 border rounded-3 text-center bg-light">
                                <i class="bi bi-person-bounding-box d-block fs-2 mb-2" style="color: #8b6262;"></i>
                                <input type="file" name="selfie_image" class="form-control form-control-sm" accept="image/*" required>
                            </div>
                        </div>
                    </div>

                    <!-- Note Keamanan -->
                    <div class="alert border-0 small d-flex align-items-center mb-4 shadow-sm" style="background-color: #fff9f9; color: #8b6262; border: 1px solid #ffeded !important;">
                        <i class="bi bi-shield-lock-fill me-3 fs-4"></i>
                        <div>
                            Data Anda hanya digunakan untuk keperluan verifikasi barter. Proses verifikasi manual memakan waktu maksimal 1x24 jam.
                        </div>
                    </div>

                    <button type="submit" class="btn text-white w-100 rounded-pill py-3 fw-bold shadow-sm" 
                            style="background-color: #8b6262; transition: 0.3s;">
                        Ajukan Verifikasi Sekarang
                    </button>
                    
                    <a href="{{ url()->previous() }}" class="btn btn-link w-100 text-decoration-none mt-2 small" style="color: #8b6262;">Kembali</a>
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
        font-size: 0.7rem;
        color: #999;
    }
    .form-control {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border-color: #f1f1f1;
        background-color: #fafafa;
    }
    .form-control:focus {
        border-color: #8b6262;
        background-color: #fff;
        box-shadow: 0 0 0 0.25rem rgba(139, 98, 98, 0.1);
    }
    .input-group-text {
        border-radius: 10px 0 0 10px;
        border-color: #f1f1f1;
    }
</style>
@endsection