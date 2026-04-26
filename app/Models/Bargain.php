<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bargain extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'harga_tawaran',
        'status',
        'catatan_admin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}