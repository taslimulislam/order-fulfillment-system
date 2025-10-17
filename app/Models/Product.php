<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐17

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'name',
        'price',
        'stock_quantity',
    ];

    /**
     * Get seller product.
     *
     * @return BelongsTo
     */

    public function seller() { 
        return $this->belongsTo(User::class, 'seller_id'); 
    }

}
