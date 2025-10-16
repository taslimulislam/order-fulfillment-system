<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function getProductsForOrderWithLock(array $productIds): Collection
    {
        return Product::whereIn('id', $productIds)
            ->lockForUpdate()
            ->get();
    }

    public function decrementStock(Product $product, int $qty): void
    {
        $product->decrement('stock_quantity', $qty);
    }
}
