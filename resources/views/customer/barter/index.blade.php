@extends('layouts.main')

@section('content')
<div class="container-fluid py-5" style="background-color: #fdf5f5; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    
    <div style="width: 100%; max-width: 1100px; text-align: center;">
        
        {{-- HEADER --}}
        <header style="margin-bottom: 50px;">
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 800; color: #8b6262; font-size: 3.5rem; margin-bottom: 10px;">
                Barter Area
            </h1>
            <p style="color: #6c757d; font-size: 1.2rem;">Atur barter bajumu dan temukan gaya baru dari lemari teman</p>
        </header>

        @if(!$user->isVerified())
            {{-- BOX TERKUNCI --}}
            <div style="padding: 40px 0; animation: fadeIn 1s;">
                <div style="width: 110px; height: 110px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; box-shadow: 0 15px 35px rgba(139, 98, 98, 0.15);">
                    <i class="bi bi-shield-lock-fill" style="font-size: 3.5rem; color: #8b6262;"></i>
                </div>
                
                <h2 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #8b6262; margin-bottom: 15px;">Akses Terkunci!</h2>
                
                <div style="max-width: 500px; margin: 0 auto;">
                    @if($user->verification && $user->verification->status == 'pending')
                        <p style="color: #6c757d; margin-bottom: 30px;">Sabar ya! KTP kamu lagi dicek sama admin Gema Sandang. ✨</p>
                        {{-- BUTTON PENDING --}}
                        <div style="display: inline-block; background: #fdf5f5; color: #8b6262; border: 2px solid #8b6262; border-radius: 50px; padding: 14px 40px; font-weight: 700; letter-spacing: 1px;">
                            <i class="bi bi-hourglass-split"></i> DATA SEDANG DIPROSES
                        </div>
                    @else
                        <p style="color: #6c757d; margin-bottom: 30px;">Verifikasi KTP dulu yuk biar bisa barteran sama temen-temen lainnya!</p>
                        
                        <a href="{{ route('verification.form') }}" 
                           style="display: inline-flex; align-items: center; gap: 12px; background: linear-gradient(135deg, #8b6262 0%, #6d4b4b 100%); color: #ffffff !important; border-radius: 50px; padding: 18px 45px; font-weight: 700; font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; text-decoration: none !important; border: none; box-shadow: 0 10px 25px rgba(139, 98, 98, 0.3); transition: 0.3s;">
                            <i class="bi bi-shield-check" style="font-size: 1.2rem;"></i> 
                            VERIFIKASI SEKARANG
                        </a>
                    @endif
                </div>
            </div>
        @else
            {{-- GRID PRODUK (SUDAH VERIF) --}}
            <div class="row row-cols-1 row-cols-md-3 g-4" style="text-align: left;">
                @forelse($barterItems as $item)
                    <div class="col">
                        <div style="background: white; border-radius: 25px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.04); height: 100%;">
                            <div style="height: 250px; background: #eee;">
                                <img src="{{ asset('storage/' . $item->foto_barang) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="padding: 25px;">
                                <h6 style="font-weight: 800; margin-bottom: 5px;">{{ $item->nama_barang }}</h6>
                                <p style="font-size: 0.85rem; color: #8b6262; font-weight: 700;">Owner: @<span>{{ $item->user->username }}</span></p>
                                <a href="#" style="display: block; width: 100%; background: #8b6262; color: white; text-align: center; padding: 10px; border-radius: 50px; text-decoration: none; font-weight: 600; margin-top: 15px;">Ajukan Barter</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="width: 100%; text-align: center; padding: 50px;">
                        <p class="text-muted">Belum ada barang barter nih.</p>
                    </div>
                @endforelse
            </div>
        @endif

    </div>
</div>
@endsection