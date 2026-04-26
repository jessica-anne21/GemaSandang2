@extends('layouts.guest') 

@section('content')
<div class="container" style="padding-top: 5rem; padding-bottom: 5rem;">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            
            {{-- Logo di tengah --}}
            <div class="text-center mb-4">
                <a href="{{ route('home') }}"> 
                    <img src="{{ asset('images/logo.png') }}" alt="Gema Sandang Logo" style="height: 60px;">
                </a>
            </div>

            {{-- Card Form Login --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h2 class="text-center mb-4" style="font-family: 'Playfair Display', serif;">Admin Login</h2>

                    {{-- Menampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Email atau Password salah.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" class="form-control" type="password" name="password" required>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-custom btn-lg">
                                Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection