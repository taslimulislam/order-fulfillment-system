<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐17

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderPlaced;
use App\Jobs\GenerateInvoiceJob;


class InvoiceDispatchListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the OrderPlaced event and dispatch invoice generation.
     *
     * @param OrderPlaced $event The dispatched order event.
     * @return void
     */
    public function handle(OrderPlaced $event): void
    {
        GenerateInvoiceJob::dispatch($event->orderId);
    }
}
