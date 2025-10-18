<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐18
namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderPlaced;
use App\Models\{Order, User};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class UpdateSellerBalanceListener implements ShouldQueue

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
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The backoff intervals (in seconds) between retry attempts.
     *
     * @var array<int>
     */
    public $backoff = [10, 30, 60, 120, 300];

    /**
     * Handle the OrderPlaced event and update seller balances.
     *
     * @param OrderPlaced $event Dispatched order event.
     * @return void
     */
    public function handle(OrderPlaced $event): void
    {
        $order = Order::with('items')->findOrFail($event->orderId);

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                // Idempotency: one credit per order_item
                $key = "seller_credit_order_item_{$item->id}";
                if (Cache::add($key, true, 86400)) {
                    // Lock seller row for consistency during increment
                    User::whereKey($item->seller_id)
                        ->lockForUpdate()
                        ->increment('balance', $item->line_total ?? ($item->quantity * $item->unit_price));
                }
            }
        });

    }

}
