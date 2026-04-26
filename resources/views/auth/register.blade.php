@extends('layouts.main')

@section('content')
<div class="container my-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5"> 
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4" style="font-family: 'Playfair Display', serif;">Buat Akun Baru</h2>

                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap Anda" required autofocus />
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <div class="input-group">
        <span class="input-group-text">@</span>
        <input id="username" class="form-control" type="text" name="username" value="{{ old('username') }}" placeholder="usernameunik" required />
    </div>
    @error('username')
        <div class="text-danger mt-1 small">{{ $message }}</div>
    @enderror
</div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required />
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" class="form-control" type="password" name="password" placeholder="Minimal 8 karakter" required autocomplete="new-password" />
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" placeholder="Ulangi password" required />
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom py-2 shadow-sm" style="background-color: #8b6262; color: white;">
                                DAFTAR SEKARANG
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none" style="color: #8b6262; font-weight: 600;">Masuk di sini</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection