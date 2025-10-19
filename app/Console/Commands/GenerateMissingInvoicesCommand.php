<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐18

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InvoiceDispatchService;

/**
 * Dispatch invoice generation jobs for paid orders without existing invoices.
 */
class GenerateMissingInvoicesCommand extends Command
{
    protected $signature = 'invoices:dispatch-missing';
    protected $description = 'Dispatch invoice jobs for paid orders missing invoice files';

    protected InvoiceDispatchService $service;

    /**
     * Inject the invoice dispatch service.
     */
    public function __construct(InvoiceDispatchService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dispatchedCount = $this->service->dispatchMissingInvoices(function ($orderId) {
            $this->info("Invoice job dispatched for Order #{$orderId}");
        });

        $this->info("Total dispatched: {$dispatchedCount}");

        return Command::SUCCESS;
    }
}