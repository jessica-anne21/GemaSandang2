<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    use HasFactory;

    protected $table = 'user_verifications';

    protected $fillable = [
        'user_id', 
        'nik', 
        'ktp_path', 
        'status', 
        'rejection_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}