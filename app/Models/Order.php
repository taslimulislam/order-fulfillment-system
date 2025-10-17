<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐17
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'order_number',
        'total_amount',
        'status',
    ];

    /**
     * Get buyer who placed order.
     *
     * @return BelongsTo
     */

    public function buyer() { 
        return $this->belongsTo(User::class, 'buyer_id'); 
    }

    /**
     * Get items for specific order.
     *
     * @return HasMany
     */
    public function items() { 
        return $this->hasMany(OrderItem::class); 
    }

}
