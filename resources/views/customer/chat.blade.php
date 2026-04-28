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
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" 
                             style="width: 45px; height: 45px; border: 2px solid #800000;">
                            <span class="fw-bold" style="color: #800000;">{{ strtoupper(substr($receiver->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">{{ $receiver->name }}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-box-seam me-1"></i> Barter: {{ $barterInfo->requestedItem->nama_barang }}
                            </small>
                        </div>
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
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script>
    $(document).ready(function() {
        const chatWindow = $('#chat-window');
        const receiverId = "{{ $receiver->id }}";
        const myId = "{{ Auth::id() }}";
        const barterRequestId = "{{ $barterInfo->id }}";

        function scrollToBottom() {
            chatWindow.scrollTop(chatWindow[0].scrollHeight);
        }
        scrollToBottom();

        $('#chat-form').on('submit', function(e) {
            e.preventDefault();
            let message = $('#message-input').val().trim();
            if(message === '') return;

            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });

            $.post("{{ route('chat.send') }}", {
                _token: "{{ csrf_token() }}",
                receiver_id: receiverId,
                barter_request_id: barterRequestId,
                message: message
            }, function() {
                $('#chat-messages').append(`
                    <div class="mb-3 d-flex justify-content-end">
                        <div class="p-3 rounded-4 shadow-sm text-white" style="max-width: 75%; background-color: #800000;">
                            <span style="font-size: 0.95rem;">${message}</span>
                            <small class="d-block mt-1 text-end" style="font-size: 0.6rem; opacity: 0.7;">${time}</small>
                        </div>
                    </div>
                `);
                $('#message-input').val('');
                scrollToBottom();
            });
        });

        function initEcho() {
            if (typeof window.Echo !== 'undefined') {
                window.Echo.channel('chat-channel.' + myId)
                    .listen('.MessageSent', (e) => { 
                        if(e.message.barter_request_id == barterRequestId && e.message.sender_id == receiverId) {
                            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
                            $('#chat-messages').append(`
                                <div class="mb-3 d-flex justify-content-start">
                                    <div class="p-3 rounded-4 shadow-sm bg-white" style="max-width: 75%; border: 1px solid #eee;">
                                        <small class="d-block fw-bold mb-1" style="font-size: 0.65rem; color: #800000;">${e.sender_name}</small>
                                        <span style="font-size: 0.95rem;">${e.message.isi_pesan}</span>
                                        <small class="d-block mt-1 text-end" style="font-size: 0.6rem; opacity: 0.7;">${time}</small>
                                    </div>
                                </div>
                            `);
                            scrollToBottom();
                        }
                    });
            } else {
                setTimeout(initEcho, 1000); 
            }
        }
        initEcho();
    });
</script>
@endsection