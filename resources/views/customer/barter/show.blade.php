@extends('layouts.main')

@section('content')
<div class="container py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            {{-- Navigasi Back --}}
            <div class="mb-4">
                <a href="{{ route('barter.index') }}" class="text-decoration-none text-muted fw-bold">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Barter Area
                </a>
            </div>

            {{-- Card Detail Produk --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="row g-0">
                    
                    {{-- Foto Produk --}}
                    <div class="col-md-6">
                        <div class="h-100 bg-light d-flex align-items-center justify-content-center">
                            <img src="{{ asset('storage/' . $item->foto_barang) }}" 
                                 class="img-fluid w-100 h-100" 
                                 style="object-fit: cover; min-height: 500px;" 
                                 alt="{{ $item->nama_barang }}">
                        </div>
                    </div>

                    {{-- Detail Produk --}}
                    <div class="col-md-6 bg-white p-4 p-lg-5">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #fceaea; color: #8b6262;">
                                {{ $item->kategori }}
                            </span>
                            <span class="text-muted small">Kondisi: <strong class="text-dark">{{ $item->kondisi }}</strong></span>
                        </div>

                        <h1 class="fw-bold mb-3" style="color: #444; font-family: 'Playfair Display', serif; font-size: 2.5rem;">
                            {{ $item->nama_barang }}
                        </h1>

                        {{-- Card Pemilik --}}
                        <div class="d-flex align-items-center mb-4 p-3 rounded-4" style="background-color: #fef9f9; border: 1px solid #fceaea;">
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center text-white fw-bold" 
                                 style="width: 50px; height: 50px; background-color: #8b6262; font-size: 1.1rem;">
                                {{ strtoupper(substr($item->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="mb-0 small text-muted">Pemilik Barang</p>
                                <p class="mb-0 fw-bold text-dark">{{ $item->user->name }} 
                                    <i class="bi bi-patch-check-fill text-primary ms-1"></i>
                                </p>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h6 class="fw-bold text-uppercase small text-muted mb-3" style="letter-spacing: 1px;">Deskripsi Barang</h6>
                            <p class="text-muted" style="line-height: 1.8; font-size: 1.05rem;">
                                {{ $item->deskripsi }}
                            </p>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-grid gap-3">
                            @if($userProducts->isEmpty())
                                <div class="alert alert-warning rounded-4 small border-0 shadow-sm">
                                    <i class="bi bi-info-circle me-2"></i> Kamu belum punya barang di lemari virtual untuk dibarter. 
                                    <a href="{{ route('profile.my-profile') }}" class="fw-bold text-dark">Upload dulu yuk!</a>
                                </div>
                            @else
                                <button type="button" class="btn text-white rounded-pill py-3 fw-bold shadow-sm btn-hover-effect" 
                                        style="background-color: #8b6262; border: none; font-size: 1.1rem;"
                                        data-bs-toggle="modal" data-bs-target="#offerBarterModal">
                                    <i class="bi bi-arrow-left-right me-2"></i>Ajukan Penawaran Barter
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODAL PILIH BARANG (ISIAN LEMARI USER) --}}
@if(!$userProducts->isEmpty())
<div class="modal fade" id="offerBarterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('barter.request.send', $item->id) }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 bg-light">
                    <h5 class="modal-title fw-bold" style="font-family: 'Playfair Display'; color: #8b6262;">Pilih Barang Penukar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Pilih satu barang dari lemari virtualmu untuk ditawarkan ke <strong>{{ $item->user->name }}</strong>.</p>
                    
                    <div class="row row-cols-2 row-cols-md-3 g-3 overflow-auto" style="max-height: 400px;">
                        @foreach($userProducts as $myProduct)
                            <div class="col">
                                <input type="radio" class="btn-check" name="my_item_id" id="item{{ $myProduct->id }}" value="{{ $myProduct->id }}" required>
                                <label class="card h-100 border-2 rounded-4 overflow-hidden shadow-sm barter-option-card" for="item{{ $myProduct->id }}" style="cursor: pointer;">
                                    <img src="{{ asset('storage/' . $myProduct->foto_barang) }}" class="card-img-top" style="height: 140px; object-fit: cover;">
                                    <div class="card-body p-2 text-center">
                                        <h6 class="small fw-bold mb-0 text-truncate">{{ $myProduct->nama_barang }}</h6>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $myProduct->kondisi }}</small>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <label class="small fw-bold text-muted text-uppercase mb-2">Pesan Negosiasi</label>
                        <textarea name="pesan" class="form-control rounded-3" rows="2" placeholder="Halo kak, barter sama punyaku yang ini yuk? Kondisinya masih Like New loh!"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn text-white w-100 rounded-pill py-3 fw-bold shadow-sm btn-hover-effect" style="background-color: #8b6262;">
                        Kirim Penawaran Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    .btn-hover-effect:hover {
        background-color: #6d4a4a !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    /* Style Visual Selection */
    .barter-option-card {
        border-color: transparent;
        transition: all 0.3s ease;
    }

    .btn-check:checked + .barter-option-card {
        border-color: #8b6262 !important;
        background-color: #fff9f9;
        transform: scale(1.02);
        box-shadow: 0 10px 20px rgba(139, 98, 98, 0.1) !important;
    }

    /* Custom Scrollbar Modal */
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    ::-webkit-scrollbar-thumb {
        background: #8b6262;
        border-radius: 10px;
    }
</style>
@endsection