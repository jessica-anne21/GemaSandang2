<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'kuantitas',
        'harga_saat_beli',
    ];

    // Relasi: Satu item adalah milik satu Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi: Satu item adalah milik satu Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}