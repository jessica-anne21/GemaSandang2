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
        'cancel_reason',
        'cancelled_by',
        'admin_note',
        'method_selection',        // 'none', 'standard', 'protection'
        'sender_payment_proof',
        'receiver_payment_proof',
        'sender_payment_status',   // 'waiting', 'paid'
        'receiver_payment_status', // 'waiting', 'paid'
        'terms_accepted',    
        'sender_resi',
        'receiver_resi',
        'sender_logistic_status',
        'receiver_logistic_status',
        'resi_from_admin_to_sender',
        'resi_from_admin_to_receiver',
    ];

    /**
     * Casting data agar otomatis menjadi tipe data yang sesuai di PHP
     */
    protected $casts = [
        'terms_accepted' => 'boolean',
    ];

    // =========================================================================
    // RELASI DATA
    // =========================================================================

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

    // 3. Relasi ke Barang yang diinginkan (Item Orang Lain)
    public function requestedItem()
    {
        return $this->belongsTo(BarterItem::class, 'requested_item_id');
    }

    // 4. Relasi ke Barang yang ditawarkan (Item Kita)
    public function offeredItem()
    {
        return $this->belongsTo(BarterItem::class, 'offered_item_id');
    }

    // =========================================================================
    // ACCESSORS (PROPERTI VIRTUAL UNTUK MEMUDAHKAN DI BLADE)
    // =========================================================================

    /**
     * Cek apakah metode yang dipilih adalah Protection
     * Penggunaan di Blade: $barter->is_protected
     */
    public function getIsProtectedAttribute()
    {
        return $this->method_selection === 'protection';
    }

    /**
     * Cek apakah kedua belah pihak sudah melunasi pembayaran
     * Penggunaan di Blade: $barter->is_both_paid
     */
    public function getIsBothPaidAttribute()
    {
        return $this->sender_payment_status === 'paid' && $this->receiver_payment_status === 'paid';
    }

    /**
     * Cek apakah kedua belah pihak sudah menginput resi
     * Penggunaan di Blade: $barter->is_both_shipped
     */
    public function getIsBothShippedAttribute()
    {
        return !empty($this->sender_resi) && !empty($this->receiver_resi);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'barter_request_id');
    }
}