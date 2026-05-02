<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'alamat',     
        'city',
        'district',
        'nomor_hp',   
        'bio',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Mendefinisikan relasi bahwa satu User memiliki banyak Order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany 
    {
        return $this->hasMany(Order::class);
    }

    public function bargains(): HasMany 
    {
        return $this->hasMany(Bargain::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    
    public function verification()
    {
        return $this->hasOne(UserVerification::class, 'user_id');
    }

    public function isVerified(): bool
    {
        return $this->verification && $this->verification->status === 'verified';
    }

    /**
 * Relasi ke tabel barter_items (Barang milik user)
 */
public function barterItems(): HasMany
{
    // Pastikan nama tabelnya 'barter_items' dan foreign key-nya 'user_id'
    return $this->hasMany(BarterItem::class, 'user_id');
}
    
}