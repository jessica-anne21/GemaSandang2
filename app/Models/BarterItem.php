<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarterItem extends Model
{
    use HasFactory;

    protected $table = 'barter_items';

    protected $fillable = [
        'user_id',      
        'nama_barang',  
        'deskripsi',    
        'kondisi',      
        'foto_barang',  
        'foto_lainnya',
        'kategori',     
        'size',
        'status',       
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Barang ini diposisikan sebagai barang yang DIMINTA orang lain
public function barterRequestsAsRequested()
{
    return $this->hasMany(BarterRequest::class, 'requested_item_id');
}

// Barang ini diposisikan sebagai barang PENUKAR yang ditawarkan
public function barterRequestsAsOffered()
{
    return $this->hasMany(BarterRequest::class, 'offered_item_id');
}
}