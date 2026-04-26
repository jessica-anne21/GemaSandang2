<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel; // Pastikan ini di-import
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth; // Tambahkan ini buat ambil nama pengirim

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('chat-channel.' . $this->message['receiver_id']),
        ];
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }

    // TAMBAHKAN INI JESS! Biar Javascript dapet nama pengirimnya
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'sender_name' => Auth::user()->name, // Mengambil nama Jessica/Jonathan yang lagi login
        ];
    }
}