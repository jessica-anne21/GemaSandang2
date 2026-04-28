<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarterRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 
        'receiver_id', 
        'requested_item_id', 
        'offered_item_id', 
        'message', 
        'status',
        'otp_code',
    ];

    // 1. Relasi ke Pengirim (User)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // 2. Relasi ke Penerima (User)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // 3. Relasi ke Barang yang kita pengen (Item Orang Lain)
    public function requestedItem()
    {
        return $this->belongsTo(BarterItem::class, 'requested_item_id');
    }

    // 4. Relasi ke Barang yang kita tawarin (Item Kita)
    public function offeredItem()
    {
        return $this->belongsTo(BarterItem::class, 'offered_item_id');
    }
}