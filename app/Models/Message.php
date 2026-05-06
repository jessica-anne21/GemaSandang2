<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // Nama tabel di database kamu (image_91bb74.jpg)
    protected $table = 'messages';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'barter_request_id',
        'isi_pesan',
        'image',
        'is_read'
    ];

    /**
     * Relasi ke User (Pengirim)
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relasi ke User (Penerima)
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Relasi ke Barter Request (Konteks Transaksi)
     */
    public function barterRequest()
    {
        return $this->belongsTo(BarterRequest::class, 'barter_request_id');
    }
}