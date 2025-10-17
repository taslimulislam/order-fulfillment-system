<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐17

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'seller_id',
        'quantity',
        'unit_price',
        'line_total',
    ];

    /**
     * Order details of order item.
     *
     * @return BelongsTo
     */
    public function order() { 
        return $this->belongsTo(Order::class); 
    }

    /**
     * Get product associated with order item.
     *
     * @return BelongsTo
     */
    public function product() { 
        return $this->belongsTo(Product::class); 
    }

    /**
     * Get seller associated with order item.
     *
     * @return BelongsTo
     */
    public function seller() { 
        return $this->belongsTo(User::class, 'seller_id'); 
    }

}
