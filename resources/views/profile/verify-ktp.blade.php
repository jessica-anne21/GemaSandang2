@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h3 class="fw-bold mb-4" style="font-family: 'Playfair Display'; color: #8b6262;">Verifikasi Identitas</h3>
                <p class="text-muted small">Halo {{ $user->name }}, silakan upload KTP kamu untuk membuka fitur Barter.</p>
                
                @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                <form action="{{ route('verification.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor NIK (16 Digit)</label>
                        <input type="text" name="nik" class="form-control" placeholder="3201..." maxlength="16" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Foto KTP</label>
                        <input type="file" name="ktp_image" class="form-control" accept="image/*" required>
                        <div class="form-text">Pastikan foto terlihat jelas dan tidak blur.</div>
                    </div>
                    <button type="submit" class="btn text-white w-100 rounded-pill py-2" style="background-color: #8b6262;">
                        Kirim untuk Verifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection