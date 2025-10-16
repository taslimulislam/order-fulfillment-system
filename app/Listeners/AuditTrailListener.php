<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AuditTrailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $order = Order::with('items')->find($event->orderId);
        if (! $order) {
            return;
        }

        $payload = [
            'type' => 'order_placed',
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'buyer_id' => $order->buyer_id,
            'total_amount' => $order->total_amount,
            'items' => $order->items->map(fn ($i) => [
                'order_item_id' => $i->id,
                'seller_id' => $i->seller_id,
                'product_id' => $i->product_id,
                'quantity' => $i->quantity,
                'unit_price' => $i->unit_price,
                'line_total' => $i->line_total,
            ])->toArray(),
            'timestamp' => now()->toIso8601String(),
        ];

        Log::channel('orders')->info(json_encode($payload));
    }

}
