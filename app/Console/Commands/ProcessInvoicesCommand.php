<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐18

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Jobs\GenerateInvoiceJob;
use Illuminate\Support\Facades\Storage;

class ProcessInvoicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch invoice jobs for paid but uninvoiced orders';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $orders = Order::where('status', 'paid')->get();

        $count = 0;

        foreach ($orders as $order) {
            $path = "invoices/invoice_{$order->id}.json";

            if (! Storage::exists($path)) {
                GenerateInvoiceJob::dispatch($order->id);
                $this->info("Invoice job dispatched for Order #{$order->id}");
                $count++;
            }
        }

        $this->info("Total dispatched: {$count}");

        return Command::SUCCESS;
    }
}
