<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐17

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param int $orderId The ID of the order to process.
     */
    public function __construct(public readonly int $orderId)
    {
        //
    }

    /**
     * Execute the job and generate the invoice file.
     *
     * @return void
     */
    public function handle(): void
    {
        $order = Order::with('items.product', 'buyer')->findOrFail($this->orderId);

        $path = "invoices/invoice_{$order->id}.json";
        if (Storage::exists($path)) {
            return; // idempotent
        }

        $invoice = [
            'order_number' => $order->order_number,
            'buyer' => [
                'id' => $order->buyer->id,
                'name' => $order->buyer->name,
                'email' => $order->buyer->email,
            ],
            'items' => $order->items->map(fn ($i) => [
                'name' => $i->product->name,
                'quantity' => $i->quantity,
                'unit_price' => $i->unit_price,
                'line_total' => $i->line_total,
            ])->toArray(),
            'total_amount' => $order->total_amount,
            'generated_at' => now()->toIso8601String(),
        ];

        Storage::put($path, json_encode($invoice));
    }

}
