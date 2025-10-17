<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐17

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderObserver
{

    /**
     * Handle the "creating" event for the Order model.
     * Generates a unique order number in the format:ORD-YYYYMMDD-XXXXXX
     *
     * @param Order $order Order instance being created.
     * @return void
     */
    public function creating(Order $order): void
    {
        if (!$order->order_number) {
            $order->order_number = sprintf(
                'ORD-%s-%s',
                now()->format('Ymd'),
                Str::upper(Str::random(6))
            );
        }
    }
}
