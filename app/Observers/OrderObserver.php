<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderObserver
{
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
