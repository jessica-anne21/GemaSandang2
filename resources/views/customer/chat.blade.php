@extends('layouts.main')

@section('content')
<div class="container py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden chat-container">
                
                <div class="card-header bg-white p-3 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        {{-- Link Back: Ke Index saja (tanpa parameter) --}}
                        <a href="{{ route('chat.index') }}" class="text-dark me-3">
                            <i class="bi bi-arrow-left fs-4"></i>
                        </a>
                        
                        {{-- Link Profil: Pastikan HANYA mengirim partner ID --}}
                        <a href="{{ route('profile.public', $partner->id) }}" class="d-flex align-items-center text-decoration-none">
                            {{-- Foto & Nama Partner --}}
                            <div class="rounded-circle ...">
                                {{-- Foto Profil --}}
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $partner->name }}</h6>
                                <small class="text-muted fw-bold" style="font-size: 0.75rem;">
                                    <i class="bi bi-box-seam me-1"></i> Barter: {{ $barter->offeredItem->nama_barang }}
                                </small>
                            </div>
                        </a>
                    </div>

                    {{-- Tombol Lihat Progress: Gunakan parameter 'id' sesuai web.php kamu --}}
                    <div class="text-end">
                        <a href="{{ route('barter.tracking', ['id' => $barter->id]) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold">
                            Lihat Progress
                        </a>
                    </div>
                </div>

                {{-- WINDOW CHAT --}}
                <div id="chat-window" class="card-body bg-light position-relative" style="height: 500px; overflow-y: auto; padding: 25px; scroll-behavior: smooth;">
                    <div id="chat-messages">
                        @if($messages->isEmpty())
                            {{-- TAMPILAN JIKA BELUM ADA CHAT --}}
                            <div id="empty-state" class="text-center py-5 opacity-50 animate-fade-in mt-5">
                                <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center shadow-sm mb-3" style="width: 80px; height: 80px;">
                                    <i class="bi bi-chat-dots fs-1 text-maroon"></i>
                                </div>
                                <h6 class="fw-bold">Belum ada percakapan</h6>
                                <p class="small">Mulai negosiasi atau tanya kondisi barang sekarang!</p>
                            </div>
                        @else
                            @foreach($messages as $msg)
                                <div class="mb-4 d-flex {{ $msg->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div class="message-bubble shadow-sm p-3 {{ $msg->sender_id == Auth::id() ? 'bg-maroon text-white rounded-sent' : 'bg-white text-dark border rounded-received' }}" style="max-width: 80%;">
                                        
                                        @if($msg->sender_id != Auth::id())
                                            <small class="d-block fw-bold mb-1" style="font-size: 0.65rem; color: #800000;">{{ $partner->name }}</small>
                                        @endif

                                        {{-- TAMPILAN GAMBAR --}}
                                        @if($msg->image)
                                            <a href="{{ asset('storage/' . $msg->image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $msg->image) }}" class="img-fluid rounded-3 mb-2 d-block shadow-sm chat-img">
                                            </a>
                                        @endif

                                        <span class="isi-pesan" style="font-size: 0.95rem; line-height: 1.4;">{{ $msg->isi_pesan }}</span>
                                        
                                        <div class="d-flex align-items-center justify-content-end mt-1" style="font-size: 0.65rem; opacity: 0.8;">
                                            <span>{{ $msg->created_at->format('H:i') }}</span>
                                            @if($msg->sender_id == Auth::id())
                                                <i class="bi bi-check2-all {{ $msg->is_read ? 'text-info' : '' }} ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- FOOTER INPUT --}}
                <div class="card-footer bg-white p-3 border-top-0">
                    {{-- Preview Gambar Sebelum Kirim --}}
                    <div id="image-preview-container" class="mb-3 d-none animate-fade-in">
                        <div class="position-relative d-inline-block p-2 bg-light rounded-4 border">
                            <img id="image-preview" src="" class="rounded-3 shadow-sm" style="height: 100px; width: 100px; object-fit: cover;">
                            <button type="button" id="remove-preview" class="btn btn-danger btn-sm position-absolute top-0 start-100 translate-middle rounded-circle shadow">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>

                    <form id="chat-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $partner->id }}">
                        <input type="hidden" name="barter_request_id" value="{{ $barter->id }}">

                        <div class="d-flex align-items-center gap-2">
                            {{-- Button Attachment --}}
                            <label for="image-input" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" 
                                    style="width: 48px; height: 48px; cursor: pointer; transition: 0.2s;">
                                <i class="bi bi-image text-maroon fs-5"></i>
                                <input type="file" id="image-input" name="image" class="d-none" accept="image/*">
                            </label>

                            <div class="position-relative flex-grow-1">
                                <input type="text" id="message-input" name="message"
                                       class="form-control border-0 bg-light rounded-pill px-4 py-2 shadow-none" 
                                       placeholder="Tulis pesan..." 
                                       autocomplete="off">
                            </div>
                            
                            <button class="btn btn-maroon rounded-circle shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" 
                                    style="width: 48px; height: 48px;" type="submit" id="btn-send">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSS KHUSUS CHAT --}}
<style>
    .bg-maroon { background-color: #800000 !important; }
    .text-maroon { color: #800000 !important; }
    .bg-soft-maroon { background-color: #fff0f0; }
    .btn-maroon { background-color: #800000; color: white; transition: 0.3s; }
    .btn-maroon:hover { background-color: #600000; color: white; transform: scale(1.05); }
    .btn-maroon:disabled { opacity: 0.6; }
    
    .rounded-sent { border-radius: 20px 20px 4px 20px; }
    .rounded-received { border-radius: 20px 20px 20px 4px; }
    
    .chat-container { border: 1px solid rgba(128, 0, 0, 0.1); }
    .chat-img { transition: 0.3s; cursor: pointer; }
    .chat-img:hover { filter: brightness(0.9); }
    
    .hover-maroon:hover { color: #800000 !important; }
    .no-scrollbar::-webkit-scrollbar { display: none; }

    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* Custom Scrollbar */
    #chat-window::-webkit-scrollbar { width: 6px; }
    #chat-window::-webkit-scrollbar-track { background: transparent; }
    #chat-window::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 10px; }
    #chat-window::-webkit-scrollbar-thumb:hover { background: #d0d0d0; }
</style>

{{-- JAVASCRIPT CHAT --}}
<script>
    const chatWindow = document.getElementById('chat-window');
    const imageInput = document.getElementById('image-input');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImg = document.getElementById('image-preview');
    const removePreview = document.getElementById('remove-preview');
    const chatForm = document.getElementById('chat-form');
    const emptyState = document.getElementById('empty-state');

    // 1. Auto-scroll ke paling bawah saat load
    chatWindow.scrollTop = chatWindow.scrollHeight;

    // 2. Handle Preview Gambar
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) { // Limit 2MB
                alert('Ukuran file terlalu besar (Maks 2MB)');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                previewContainer.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });

    removePreview.addEventListener('click', () => {
        imageInput.value = '';
        previewContainer.classList.add('d-none');
    });

    // 3. Handle Submit Chat (AJAX)
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btnSend = document.getElementById('btn-send');
        const messageInput = document.getElementById('message-input');
        const formData = new FormData(this);

        if (!messageInput.value.trim() && !imageInput.files[0]) return;

        btnSend.disabled = true;

        fetch("{{ route('chat.send') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'Success') {
                if(emptyState) emptyState.remove();

                // Append Bubble Chat Secara Dinamis (Optimistic UI)
                const messagesContainer = document.getElementById('chat-messages');
                let imgHtml = data.data.image_url ? `<img src="${data.data.image_url}" class="img-fluid rounded-3 mb-2 d-block shadow-sm">` : '';
                
                const newBubble = `
                    <div class="mb-4 d-flex justify-content-end animate-fade-in">
                        <div class="bg-maroon text-white p-3 shadow-sm rounded-sent" style="max-width: 80%;">
                            ${imgHtml}
                            <span style="font-size: 0.95rem;">${data.data.isi_pesan ?? ''}</span>
                            <div class="d-flex align-items-center justify-content-end mt-1" style="font-size: 0.65rem; opacity: 0.8;">
                                <span>${data.data.created_at}</span>
                                <i class="bi bi-check2 ms-1"></i>
                            </div>
                        </div>
                    </div>
                `;
                
                messagesContainer.insertAdjacentHTML('beforeend', newBubble);
                
                // Reset Form
                messageInput.value = '';
                imageInput.value = '';
                previewContainer.classList.add('d-none');
                
                // Scroll ke bawah
                chatWindow.scrollTop = chatWindow.scrollHeight;
            }
            btnSend.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            btnSend.disabled = false;
            alert('Gagal mengirim pesan.');
        });
    });
</script>
@endsection