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
        'kategori',     
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
}