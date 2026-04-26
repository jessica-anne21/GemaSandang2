<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trend extends Model
{
    use HasFactory;

    protected $table = 'trends';

    protected $fillable = [
    'judul', 
    'deskripsi', 
    'gambar', 
    'sumber', 
    'link_sumber', 
    'style', 
    'warna', 
    'material',    
    'skor_popularitas',
    'status'
];

   
    public function comments()
    {
        return $this->hasMany(Comment::class, 'trend_id');
    }


    public function isLikedBy($user)
    {
        if (!$user) return false;

        return false; 
    }
}