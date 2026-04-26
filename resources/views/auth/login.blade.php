@extends('layouts.main')

@section('content')
<div class="container my-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4" style="font-family: 'Playfair Display', serif;">Login</h2>

                    @if (session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required autofocus />
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" class="form-control" type="password" name="password" placeholder="********" required autocomplete="current-password" />
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom py-2 shadow-sm">
                                MASUK
                            </button>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted">Baru di Gema Sandang? <a href="{{ route('register') }}" class="text-decoration-none" style="color: #8b6262; font-weight: 600;">Daftar Sekarang</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection