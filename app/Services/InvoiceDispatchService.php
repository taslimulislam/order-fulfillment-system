<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐19

namespace App\Services;

use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Storage;
use App\Jobs\GenerateInvoiceJob;

class InvoiceDispatchService
{
    protected OrderRepository $repository;

    /**
     * Inject the order repository.
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Dispatch invoice jobs for paid orders missing invoice files.
     *
     * @param callable|null $onDispatch Callback for each dispatched order ID
     * @return int Number of jobs dispatched
     */
    public function dispatchMissingInvoices(?callable $onDispatch = null): int
    {
        $orders = $this->repository->getPaidOrders();
        $count = 0;

        foreach ($orders as $order) {
            $path = "invoices/invoice_{$order->id}.json";

            if (!Storage::exists($path)) {
                GenerateInvoiceJob::dispatch($order->id);
                if ($onDispatch) {
                    $onDispatch($order->id);
                }
                $count++;
            }
        }

        return $count;
    }
}