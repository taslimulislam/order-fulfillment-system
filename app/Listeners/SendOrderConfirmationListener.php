<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐20

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class SendOrderConfirmationListener implements ShouldQueue
{

    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * The number of times the job may be attempted before failing.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The backoff intervals (in seconds) between retry attempts.
     *
     * @var array<int>
     */
    public $backoff = [10, 60, 180];

    /**
     * Handle the OrderPlaced event and log confirmation details.
     *
     * @param OrderPlaced $event Dispatched order event.
     * @return void
     */
    public function handle(OrderPlaced $event): void
    {
        $order = Order::with('buyer', 'items.product')->findOrFail($event->orderId);

        Log::info('Order confirmation queued', [
            'order_id' => $order->id,
            'email' => $order->buyer->email,
        ]);

        Mail::to($order->buyer->email)->send(new OrderConfirmationMail($order));

    }

}
