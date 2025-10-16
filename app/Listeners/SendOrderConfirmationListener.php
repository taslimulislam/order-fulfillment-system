<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;


class SendOrderConfirmationListener implements ShouldQueue
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
    public $tries = 3;
    public $backoff = [10, 60, 180];

    public function handle(OrderPlaced $event): void
    {
        $order = Order::with('buyer', 'items.product')->findOrFail($event->orderId);

        // Example: replace with Mail + Mailable in real app
        Log::info('Order confirmation queued', [
            'order_id' => $order->id,
            'email' => $order->buyer->email,
        ]);

    }

}
