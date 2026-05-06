@extends('layouts.main')

@section('content')
<div class="container py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="row g-0 shadow-sm rounded-4 overflow-hidden bg-white position-relative" style="height: 75vh;">

        @php
            $isVerified = auth()->user()->verification && auth()->user()->verification->status == 'verified';
        @endphp
        
        {{-- OVERLAY AKSES TERKUNCI --}}
        @if(!$isVerified)
        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
             style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(4px); z-index: 100; border-radius: 1rem;">
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
        <div class="col-md-4 border-end bg-light d-flex flex-column">
            <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0" style="color: #800000;">
                    <i class="bi bi-chat-square-dots-fill me-2"></i> Pesan Barter
                </h5>
                <span class="badge rounded-pill bg-success small">Deal Only</span>
            </div>

            <div class="overflow-auto h-100 no-scrollbar">
                @forelse($chatList as $chat)
                    @php
                        $opponent = ($chat->sender_id == Auth::id()) ? $chat->receiver : $chat->sender;
                        $targetItem = ($chat->sender_id == Auth::id()) ? $chat->requestedItem : $chat->offeredItem;
                        // Ambil unread_count dari controller
                        $unread = $chat->unread_count; 
                    @endphp
                    <a href="{{ route('chat.show', ['user_id' => $opponent->id, 'barter_id' => $chat->id]) }}" 
                       class="d-flex align-items-center p-3 text-decoration-none border-bottom hover-bg-white position-relative {{ request()->is('chat/user/'.$opponent->id.'/barter/'.$chat->id) ? 'bg-white border-start border-4 border-maroon-active' : '' }}">
                        
                        <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center me-3 position-relative" 
                             style="width: 52px; height: 52px; border: 2px solid #800000; flex-shrink: 0; overflow: visible;">
                            
                            @if($opponent->profile_photo)
                                <img src="{{ asset('storage/' . $opponent->profile_photo) }}" class="rounded-circle" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <span class="fw-bold" style="color: #800000;">{{ strtoupper(substr($opponent->name, 0, 1)) }}</span>
                            @endif

                            {{-- NOTIFIKASI DOT (Jika ada unread) --}}
                            @if($unread > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem; z-index: 10;">
                                    {{ $unread > 9 ? '9+' : $unread }}
                                </span>
                            @endif
                        </div>

                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold {{ $unread > 0 ? 'text-dark' : 'text-secondary opacity-75' }} text-truncate" style="max-width: 70%;">
                                    {{ $opponent->name }}
                                </h6>
                                <small class="text-muted" style="font-size: 0.6rem;">ID #{{ $chat->id }}</small>
                            </div>
                            <small class="{{ $unread > 0 ? 'text-maroon fw-bold' : 'text-muted fw-medium' }} text-truncate d-block" style="font-size: 0.75rem;">
                                <i class="bi bi-box-seam me-1"></i> {{ $targetItem->nama_barang ?? 'Barang Barter' }}
                            </small>
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
    .border-maroon-active { border-left: 4px solid #800000 !important; background-color: white !important; }
    .text-maroon { color: #800000 !important; }
    
    /* Animasi pulse untuk unread badge */
    .bg-danger {
        animation: pulse-red 2s infinite;
    }
    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { box-shadow: 0 0 0 5px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
</style>
@endsection