<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐19

namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;

class OrderReportRepository
{
    /**
     * Get all products of seller.
     *
     * @param int $sellerId
     * @return \Illuminate\Support\Collection
     */
    public function getSellerProducts(int $sellerId)
    {
        return Product::where('seller_id', $sellerId)
            ->get(['id', 'name', 'price']);
    }

    /**
     * Get all orders that include products of seller.
     *
     * @param int $sellerId
     * @return \Illuminate\Support\Collection
     */

    public function getOrdersWithSellerProducts(int $sellerId)
    {
        return Order::whereHas('items.product', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->with([
                'items' => function ($query) use ($sellerId) {
                    $query->whereHas('product', function ($q) use ($sellerId) {
                        $q->where('seller_id', $sellerId);
                    })->with('product:id,name');
                }
            ])
            ->get(['id', 'order_number', 'buyer_id', 'total_amount', 'status']);
    }

    /**
     * Get all orders placed by the buyer.
     *
     * @param int $buyerId
     * @return \Illuminate\Support\Collection
     */
    public function getBuyerOrders(int $buyerId)
    {
        return Order::where('buyer_id', $buyerId)
            ->with([
                'items.product' => function ($query) {
                    $query->select('id', 'name', 'price');
                }
            ])
            ->get(['id', 'order_number', 'total_amount', 'status']);
    }

}
