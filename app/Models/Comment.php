<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'trend_id',
        'user_id',
        'isi_komentar'
    ];


    public function trend()
    {
        return $this->belongsTo(Trend::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}