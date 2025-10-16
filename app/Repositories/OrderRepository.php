<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function createOrder(int $buyerId, array $payload): Order
    {
        return Order::create([
            'buyer_id' => $buyerId,
            'total_amount' => $payload['total_amount'],
            'status' => 'processing',
            // order_number set by observer
        ]);
    }

    public function createItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            $order->items()->create($item);
        }
    }
}
