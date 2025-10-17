<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐17

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    /**
     * Retrieve products by ID with a database lock for transactional safety.
     * Uses `lockForUpdate()` to prevent race conditions when modifying stock
     *
     * @param array<int> $productIds Array of product IDs.
     * @return Collection<Product> Locked collection of product.
     */
    public function getProductsForOrderWithLock(array $productIds): Collection
    {
        return Product::whereIn('id', $productIds)
            ->lockForUpdate()
            ->get();
    }

    /**
     * Decrement the stock quantity of a product.
     *
     * @param Product $product The product to update.
     * @param int $qty Subtract Quantity.
     * @return void
     */
    public function decrementStock(Product $product, int $qty): void
    {
        $product->decrement('stock_quantity', $qty);
    }
}
