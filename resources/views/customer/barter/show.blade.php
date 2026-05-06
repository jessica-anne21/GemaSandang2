@extends('layouts.main')

@section('content')
<div class="container py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            {{-- Navigasi Back --}}
            <div class="mb-4 animate-fade-in">
                <a href="{{ route('barter.index') }}" class="text-decoration-none text-muted fw-bold transition-hover">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Barter Area
                </a>
            </div>

            {{-- Card Detail Produk --}}
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate-fade-in">
                <div class="row g-0">
                    
                    {{-- Foto Produk (MULTIPLE PHOTOS) --}}
                    <div class="col-md-6 bg-white border-end">
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0" class="active"></button>
                                @php $others = json_decode($item->foto_lainnya); @endphp
                                @if($others)
                                    @foreach($others as $index => $photo)
                                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{ $index + 1 }}"></button>
                                    @endforeach
                                @endif
                            </div>

                            <div class="carousel-inner h-100">
                                <div class="carousel-item active">
                                    <img src="{{ asset('storage/' . $item->foto_barang) }}" 
                                         class="d-block w-100" 
                                         style="object-fit: cover; height: 550px;" 
                                         alt="{{ $item->nama_barang }}">
                                </div>
                                @if($others)
                                    @foreach($others as $photo)
                                        <div class="carousel-item">
                                            <img src="{{ asset('storage/' . $photo) }}" 
                                                 class="d-block w-100" 
                                                 style="object-fit: cover; height: 550px;" 
                                                 alt="{{ $item->nama_barang }}">
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            @if($others)
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon shadow-sm rounded-circle"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon shadow-sm rounded-circle"></span>
                                </button>
                            @endif
                        </div>

                        {{-- THUMBNAILS --}}
                        @if($others)
                            <div class="d-flex gap-2 p-3 overflow-auto no-scrollbar bg-light">
                                <div class="thumb-box active" onclick="$('#productCarousel').carousel(0)">
                                    <img src="{{ asset('storage/' . $item->foto_barang) }}" class="rounded-2" style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;">
                                </div>
                                @foreach($others as $index => $photo)
                                    <div class="thumb-box" onclick="$('#productCarousel').carousel({{ $index + 1 }})">
                                        <img src="{{ asset('storage/' . $photo) }}" class="rounded-2" style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Detail Produk --}}
                    <div class="col-md-6 bg-white p-4 p-lg-5 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #fff0f0; color: #800000; border: 1px solid #ffcccc;">
                                {{ $item->kategori }}
                            </span>
                            <div class="d-flex gap-2">
                                {{-- INFO SIZE & KONDISI --}}
                                <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">Size: <strong>{{ $item->size ?? '-' }}</strong></span>
                                <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">Kondisi: <strong>{{ $item->kondisi }}</strong></span>
                            </div>
                        </div>

                        <h1 class="fw-bold mb-2" style="color: #444; font-family: 'Playfair Display', serif; font-size: 2.3rem;">
                            {{ $item->nama_barang }}
                        </h1>

                        {{-- Info Pemilik & Lokasi --}}
                        <div class="mb-4 p-3 rounded-4 shadow-sm" style="background-color: #fef9f9; border: 1px solid #fceaea;">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                     style="width: 45px; height: 45px; background-color: #800000; font-size: 1rem;">
                                    {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold text-dark">{{ $item->user->name }}</p>
                                    <p class="mb-0 small text-muted">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        {{ $item->user->district ?? 'Kecamatan' }}, {{ $item->user->city ?? 'Kota' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase small text-muted mb-2" style="letter-spacing: 1px;">Deskripsi Barang</h6>
                            <p class="text-muted" style="line-height: 1.7; font-size: 1rem;">
                                {{ $item->deskripsi }}
                            </p>
                        </div>

                        {{-- TOMBOL AKSI --}}
                        <div class="mt-auto pt-4 border-top">
                            @php
                                $barterRequest = \App\Models\BarterRequest::where('requested_item_id', $item->id)
                                                ->where('sender_id', auth()->id())
                                                ->first();
                                $userProducts = auth()->user()->barterItems()->where('status', 'available')->get();
                            @endphp

                            @if($item->user_id == auth()->id())
                                <div class="alert alert-info rounded-4 small border-0 shadow-sm text-center">
                                    Ini adalah barang milikmu sendiri.
                                </div>
                            @elseif($userProducts->isEmpty())
                                <div class="alert alert-warning rounded-4 small border-0 shadow-sm">
                                    <i class="bi bi-info-circle me-2"></i> Kamu belum punya barang di Lemari Virtual. 
                                    <a href="{{ route('profile.my-profile') }}" class="fw-bold text-dark">Upload dulu yuk!</a>
                                </div>
                            @elseif($barterRequest && $barterRequest->status == 'accepted')
                                <a href="{{ route('chat.show', $barterRequest->id) }}" class="btn text-white rounded-pill w-100 py-3 fw-bold shadow-sm transition-hover" 
                                   style="background-color: #800000; font-size: 1.1rem;">
                                    <i class="bi bi-chat-dots-fill me-2"></i> Lanjut ke Chat Negosiasi
                                </a>
                            @elseif($barterRequest && $barterRequest->status == 'pending')
                                <button class="btn btn-secondary w-100 rounded-pill py-3 fw-bold shadow-sm" disabled>
                                    <i class="bi bi-clock-history me-2"></i> Penawaran Sedang Diproses...
                                </button>
                            @else
                                <button type="button" class="btn text-white w-100 rounded-pill py-3 fw-bold shadow-sm btn-hover-effect" 
                                        style="background-color: #800000; font-size: 1.1rem;"
                                        data-bs-toggle="modal" data-bs-target="#offerBarterModal">
                                    <i class="bi bi-arrow-left-right me-2"></i> Ajukan Penawaran Barter
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PILIH BARANG --}}
@if(!$userProducts->isEmpty())
<div class="modal fade" id="offerBarterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('barter.request.send', $item->id) }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 bg-light">
                    <h5 class="modal-title fw-bold" style="color: #800000; font-family: 'Playfair Display';">Pilih Barang Penukar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Pilih salah satu barang dari lemari virtualmu untuk ditawarkan kepada <strong>{{ $item->user->name }}</strong>.</p>
                    
                    <div class="row row-cols-2 row-cols-md-3 g-3 overflow-auto" style="max-height: 400px; padding: 10px;">
                        @foreach($userProducts as $myProduct)
                            <div class="col">
                                <input type="radio" class="btn-check" name="my_item_id" id="item{{ $myProduct->id }}" value="{{ $myProduct->id }}" required>
                                <label class="card h-100 border-2 rounded-4 overflow-hidden shadow-sm barter-option-card" for="item{{ $myProduct->id }}" style="cursor: pointer;">
                                    <img src="{{ asset('storage/' . $myProduct->foto_barang) }}" class="card-img-top" style="height: 140px; object-fit: cover;">
                                    <div class="card-body p-2 text-center">
                                        <h6 class="small fw-bold mb-1 text-truncate">{{ $myProduct->nama_barang }}</h6>
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-light text-dark border py-1" style="font-size: 0.6rem;">Size: {{ $myProduct->size ?? '-' }}</span>
                                            <small class="text-muted" style="font-size: 0.65rem;">{{ $myProduct->kondisi }}</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <label class="small fw-bold text-muted text-uppercase mb-2">Pesan Tambahan (Opsional)</label>
                        <textarea name="pesan" class="form-control rounded-3 shadow-none" rows="2" placeholder="Halo kak, mau tukeran sama barang ini?"></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 bg-light">
                    <button type="submit" class="btn text-white rounded-pill px-5 fw-bold shadow-sm w-100" style="background-color: #800000;">
                        Kirim Penawaran Barter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: rgba(128, 0, 0, 0.5);
        padding: 20px;
    }
    .thumb-box { border: 2px solid transparent; transition: 0.2s; }
    .thumb-box.active { border-color: #800000; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    
    .animate-fade-in { animation: fadeIn 0.5s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .transition-hover:hover { opacity: 0.8; transition: 0.3s; }
    .btn-hover-effect:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(128, 0, 0, 0.2) !important; transition: 0.3s; }
    
    .barter-option-card { border-color: transparent; transition: all 0.3s ease; }
    .btn-check:checked + .barter-option-card { border-color: #800000 !important; background-color: #fff9f9; transform: scale(1.02); }
</style>
@endsection