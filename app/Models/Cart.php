<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'product_id',
        'kuantitas',
        'harga',
        'is_bargain',
        'bargain_id',
    ];

    /**
     * Relasi ke User (Satu item keranjang milik satu User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Product 
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke Bargain 
     */
    public function bargain()
    {
        return $this->belongsTo(Bargain::class);
    }
}