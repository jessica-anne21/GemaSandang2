@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row g-0 shadow-sm rounded-4 overflow-hidden bg-white" style="height: 70vh;">
        <div class="col-md-4 border-end bg-light">
            <div class="p-3 border-bottom bg-white">
                <h5 class="fw-bold mb-0" style="color: #800000;">
                    <i class="bi bi-chat-square-dots-fill me-2"></i> Pesan Anda
                </h5>
            </div>
            <div class="overflow-auto h-100 no-scrollbar">
                @forelse($chatList as $chat)
                    <a href="{{ route('chat.show', $chat->id) }}" class="d-flex align-items-center p-3 text-decoration-none border-bottom hover-bg-white">
                        <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; border: 2px solid #800000;">
                            <span class="fw-bold" style="color: #800000;">{{ strtoupper(substr($chat->name, 0, 1)) }}</span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold text-dark">{{ $chat->name }}</h6>
                            <small class="text-muted">Klik untuk balas pesan...</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                @empty
                    <div class="text-center p-5 mt-5">
                        <i class="bi bi-chat-left-dots display-1 text-light"></i>
                        <p class="text-muted mt-3">Belum ada obrolan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="col-md-8 d-none d-md-flex align-items-center justify-content-center bg-white">
            <div class="text-center">
                <div class="mb-3">
                    <i class="bi bi-chat-heart" style="font-size: 4rem; color: #eee;"></i>
                </div>
                <h5 class="text-muted">Pilih pesan untuk mulai mengobrol</h5>
                <p class="small text-secondary">Diskusi tren atau nego barter jadi lebih mudah.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-bg-white:hover { background-color: #fff !important; transition: 0.3s; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>
@endsection