@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                {{-- Header --}}
                <div class="card-header bg-white p-3 border-bottom d-flex align-items-center">
                    <a href="{{ route('chat.index') }}" class="text-dark me-3"><i class="bi bi-arrow-left"></i></a>
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; border: 1px solid #800000;">
                        <span class="fw-bold" style="color: #800000;">{{ strtoupper(substr($receiver->name, 0, 1)) }}</span>
                    </div>
                    <h6 class="fw-bold mb-0">{{ $receiver->name }}</h6>
                </div>

                {{-- Window Chat --}}
                <div id="chat-window" class="card-body bg-light" style="height: 450px; overflow-y: auto; padding: 20px;">
                    <div id="chat-messages">
                        @foreach($messages as $msg)
                            <div class="mb-3 d-flex {{ $msg->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="p-3 rounded-4 shadow-sm" style="max-width: 70%; {{ $msg->sender_id == Auth::id() ? 'background-color: #800000; color: white;' : 'background-color: white;' }}">
                                    <small class="d-block fw-bold mb-1" style="font-size: 0.7rem;">
                                        {{ $msg->sender_id == Auth::id() ? 'You' : $receiver->name }}
                                    </small>
                                    {{ $msg->isi_pesan }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Footer Input --}}
                <div class="card-footer bg-white p-3 border-0">
                    <form id="chat-form">
                        @csrf
                        <div class="input-group">
                            <input type="text" id="message-input" class="form-control border-0 bg-light rounded-pill px-4" placeholder="Tulis pesan..." autocomplete="off">
                            <button class="btn ms-2 rounded-circle" style="background-color: #800000; color: white; width: 45px; height: 45px;" type="submit">
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
        console.log("Script Chat Berhasil Dimuat!");
        
        const chatWindow = $('#chat-window');
        const receiverId = "{{ $receiver->id }}";
        const myId = "{{ Auth::id() }}";

        function scrollToBottom() {
            chatWindow.scrollTop(chatWindow[0].scrollHeight);
        }

        scrollToBottom();

        $('#chat-form').on('submit', function(e) {
            e.preventDefault();
            let message = $('#message-input').val().trim();
            if(message === '') return;

            $.post("{{ route('chat.send') }}", {
                _token: "{{ csrf_token() }}",
                receiver_id: receiverId,
                message: message
            }, function() {
                $('#chat-messages').append(`
                    <div class="mb-3 d-flex justify-content-end">
                        <div class="p-3 rounded-4 shadow-sm text-white" style="max-width: 70%; background-color: #800000;">
                            <small class="d-block fw-bold mb-1" style="font-size: 0.7rem;">You</small>
                            ${message}
                        </div>
                    </div>
                `);
                $('#message-input').val('');
                scrollToBottom();
            }).fail(function(xhr) {
                console.error("Gagal kirim:", xhr.responseText);
            });
        });

        function initEcho() {
            if (typeof window.Echo !== 'undefined') {
                console.log("Echo siap dengerin jalur public...");
                
                window.Echo.connector.pusher.connection.bind('state_change', function(states) {
                    console.log("Status Reverb:", states.current);
                });

                window.Echo.channel('chat-channel.' + myId)
                    .listen('.MessageSent', (e) => { 
                        console.log("BOOM! PESAN MASUK:", e);
                        
                        if(e.message.sender_id == receiverId) {
                            $('#chat-messages').append(`
                                <div class="mb-3 d-flex justify-content-start">
                                    <div class="p-3 rounded-4 shadow-sm bg-white" style="max-width: 70%;">
                                        <small class="d-block fw-bold mb-1" style="font-size: 0.7rem;">${e.sender_name}</small>
                                        ${e.message.isi_pesan}
                                    </div>
                                </div>
                            `);
                            scrollToBottom();
                        } else {
                            console.log("Pesan masuk dari user lain (ID: " + e.message.sender_id + ")");
                        }
                    });
            } else {
                console.warn("Echo belum terdeteksi, mencoba ulang dalam 1 detik...");
                setTimeout(initEcho, 1000); 
            }
        }

        initEcho();
    });
</script>
@endsection