<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐17

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'balance',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get seller products.
     *
     * @return HasMany
     */
    public function products() { 
        return $this->hasMany(Product::class, 'seller_id'); 
    }

    /**
     * Get orders placed by the user.
     *
     * @return HasMany
     */
    public function orders() { 
        return $this->hasMany(Order::class, 'buyer_id'); 
    }

    /**
     * Get seller sold items.
     *
     * @return HasMany
     */
    public function soldItems() { 
        return $this->hasMany(OrderItem::class, 'seller_id'); 
    }

}
