<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐17
namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    /**
     * Create a new order record for the specified buyer.
     *
     * Sets the initial status to "processing". The order number is assigned
     * automatically via an observer after creation.
     *
     * @param int $buyerId The ID of the buyer placing the order.
     * @param array $payload The order payload containing total amount.
     * @return Order The newly created order instance.
     */
    public function createOrder(int $buyerId, array $payload): Order
    {
        return Order::create([
            'buyer_id' => $buyerId,
            'total_amount' => $payload['total_amount'],
            'status' => 'processing',
            // order_number set by observer
        ]);
    }

    /**
     * Create order item associated with the given order.
     *
     * @param Order $order instance of order.
     * @param array $items An array of item data.
     * @return void
     */
    public function createItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            $order->items()->create($item);
        }
    }
}
