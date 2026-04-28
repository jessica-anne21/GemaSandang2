@extends('layouts.main')

@section('content')
<div class="container py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="row g-0 shadow-sm rounded-4 overflow-hidden bg-white position-relative" style="height: 75vh;">

        @php
            // Cek status verifikasi user
            // Menggunakan logika: jika ada relasi verification dan statusnya 'verified'
            $isVerified = auth()->user()->verification && auth()->user()->verification->status == 'verified';
        @endphp
        
        {{-- OVERLAY AKSES TERKUNCI --}}
        @if(!$isVerified)
        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center border-radius-4" 
             style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(4px); z-index: 100;">
            <div class="text-center p-5 shadow-lg bg-white rounded-4 border" style="max-width: 400px;">
                <div class="mb-3">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                        <i class="bi bi-lock-fill" style="font-size: 2.5rem; color: #800000;"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark">Fitur Chat Terkunci</h5>
                <p class="text-muted small mb-4">Demi keamanan transaksi barter, kamu wajib verifikasi KTP terlebih dahulu untuk mengakses fitur pesan.</p>
                <a href="{{ route('profile.my-profile') }}" class="btn text-white px-4 fw-bold rounded-pill" style="background-color: #800000;">
                    Verifikasi Sekarang
                </a>
            </div>
        </div>
        @endif

        {{-- Sidebar: Daftar Chat --}}
        <div class="col-md-4 border-end bg-light">
            <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0" style="color: #800000;">
                    <i class="bi bi-chat-square-dots-fill me-2"></i> Pesan Barter
                </h5>
                <span class="badge rounded-pill bg-success small">Deal Only</span>
            </div>

            <div class="overflow-auto h-100 no-scrollbar pb-5">
                @forelse($chatList as $chat)
                    @php
                        $opponent = ($chat->sender_id == Auth::id()) ? $chat->receiver : $chat->sender;
                    @endphp
                    <a href="{{ route('chat.show', $chat->id) }}" class="d-flex align-items-center p-3 text-decoration-none border-bottom hover-bg-white {{ request()->is('chat/'.$chat->id) ? 'bg-white border-start border-4 border-maroon-active' : '' }}">
                        <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center me-3" 
                             style="width: 52px; height: 52px; border: 2px solid #800000; flex-shrink: 0;">
                            <span class="fw-bold" style="color: #800000;">{{ strtoupper(substr($opponent->name, 0, 1)) }}</span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="mb-0 fw-bold text-dark text-truncate">{{ $opponent->name }}</h6>
                            <small class="text-muted text-truncate d-block">
                                <i class="bi bi-box-seam me-1"></i> {{ $chat->requestedItem->nama_barang }}
                            </small>
                        </div>
                        <div class="ms-2 text-end">
                             <i class="bi bi-chevron-right text-muted small"></i>
                        </div>
                    </a>
                @empty
                    <div class="text-center p-5 mt-5">
                        <i class="bi bi-chat-dots display-1 text-light"></i>
                        <p class="text-muted mt-3">Belum ada obrolan deal.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Main View: Empty State --}}
        <div class="col-md-8 d-none d-md-flex align-items-center justify-content-center bg-white text-center p-4">
            <div>
                <div class="mb-3">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 100px; height: 100px;">
                        <i class="bi bi-chat-heart" style="font-size: 3rem; color: #800000;"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark">Pilih pesan untuk mulai mengobrol</h5>
                <p class="small text-secondary px-lg-5">Hanya transaksi barter yang sudah <strong>Accepted</strong> yang muncul di sini untuk menjaga keamanan negosiasi.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-bg-white:hover { background-color: #fff !important; transition: 0.3s; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .border-maroon-active { border-left: 4px solid #800000 !important; }
    .border-radius-4 { border-radius: 1rem; }
</style>
@endsection