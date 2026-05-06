@extends('layouts.main')

@section('content')
<div class="container py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                
                {{-- Header Chat --}}
                <div class="card-header bg-white p-3 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('chat.index') }}" class="text-dark me-3">
                            <i class="bi bi-arrow-left fs-5"></i>
                        </a>
                        
                        {{-- Klik Ikon atau Nama untuk ke Profil Publik --}}
                        <a href="{{ route('profile.public', $receiver->id) }}" class="d-flex align-items-center text-decoration-none transition-hover">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2 profile-icon-hover" 
                                 style="width: 45px; height: 45px; border: 2px solid #800000; overflow: hidden;">
                                {{-- Jika ada foto profil pakai img, jika tidak pakai inisial --}}
                                @if($receiver->profile_photo)
                                    <img src="{{ asset('storage/' . $receiver->profile_photo) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span class="fw-bold" style="color: #800000;">{{ strtoupper(substr($receiver->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $receiver->name }}</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-box-seam me-1"></i> Barter: {{ $barterInfo->requestedItem->nama_barang }}
                                </small>
                            </div>
                        </a>
                    </div>
                    <span class="badge rounded-pill px-3 py-2 small" style="background-color: #fff0f0; color: #800000; border: 1px solid #ffcccc;">
                        Status: Deal
                    </span>
                </div>

                {{-- Window Chat --}}
                <div id="chat-window" class="card-body bg-light" style="height: 480px; overflow-y: auto; padding: 25px;">
                    <div id="chat-messages">
                        @foreach($messages as $msg)
                            <div class="mb-3 d-flex {{ $msg->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="p-3 rounded-4 shadow-sm" 
                                     style="max-width: 75%; {{ $msg->sender_id == Auth::id() ? 'background-color: #800000; color: white;' : 'background-color: white; border: 1px solid #eee;' }}">
                                    @if($msg->sender_id != Auth::id())
                                        <small class="d-block fw-bold mb-1" style="font-size: 0.65rem; color: #800000;">{{ $receiver->name }}</small>
                                    @endif
                                    <span style="font-size: 0.95rem;">{{ $msg->isi_pesan }}</span>
                                    <small class="d-block mt-1 text-end" style="font-size: 0.6rem; opacity: 0.7;">
                                        {{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Footer Input --}}
                <div class="card-footer bg-white p-3 border-0">
                    <form id="chat-form">
                        @csrf
                        <div class="input-group gap-2">
                            <input type="text" id="message-input" 
                                   class="form-control border-0 bg-light rounded-pill px-4 shadow-none" 
                                   placeholder="Tulis pesan negosiasi..." 
                                   autocomplete="off">
                            <button class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center" 
                                    style="background-color: #800000; color: white; width: 48px; height: 48px;" 
                                    type="submit">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Tambahan style supaya saat hover ada efek sedikit */
    .transition-hover:hover h6 {
        color: #800000 !important;
        text-decoration: underline;
    }
    .profile-icon-hover:hover {
        background-color: #fff0f0 !important;
        transform: scale(1.05);
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection