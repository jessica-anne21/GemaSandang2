<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'warna',
        'style',
        'material',
        'foto_produk', 
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems() 
    {
        return $this->hasMany(OrderItem::class); 
    }

    public function bargains()
    {
        // Satu produk bisa memiliki banyak tawaran
        return $this->hasMany(Bargain::class);
    }

    public function carts()
    {
        // Satu produk bisa ada di banyak keranjang user (sebelum dicheckout)
        return $this->hasMany(Cart::class);
    }
}