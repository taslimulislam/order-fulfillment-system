<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        private readonly ProductRepository $productRepo,
        private readonly OrderRepository $orderRepo,
    ) {}

    public function createOrder(User $buyer, array $data)
    {
        if ($buyer->role !== 'buyer') {
            throw new AuthorizationException('Only buyers can place orders.');
        }
        if (isset($data['buyer_id']) && (int) $data['buyer_id'] !== $buyer->id) {
            throw new AuthorizationException('buyer_id mismatch.');
        }

        $itemRequests = collect($data['items'] ?? []);
        if ($itemRequests->isEmpty()) {
            throw ValidationException::withMessages(['items' => 'At least one item is required.']);
        }

        return DB::transaction(function () use ($buyer, $itemRequests) {
            $productIds = $itemRequests->pluck('product_id')->all();
            $products = $this->productRepo
                ->getProductsForOrderWithLock($productIds)
                ->keyBy('id');

            $orderItems = [];
            $total = 0.0;

            /** @var Collection<int, array> $itemRequests */
            foreach ($itemRequests as $req) {
                $product = $products->get($req['product_id']);
                if (! $product) {
                    throw ValidationException::withMessages(['product_id' => "Product {$req['product_id']} not found."]);
                }

                $qty = (int) $req['quantity'];
                if ($qty <= 0) {
                    throw ValidationException::withMessages(['quantity' => 'Quantity must be positive.']);
                }
                if ($product->stock_quantity < $qty) {
                    throw ValidationException::withMessages(['stock' => "Insufficient stock for product {$product->id}."]);
                }

                $unitPrice = (float) $product->price;
                $lineTotal = $unitPrice * $qty;
                $total += $lineTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'seller_id' => $product->seller_id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $order = $this->orderRepo->createOrder($buyer->id, [
                'total_amount' => $total,
            ]);

            $this->orderRepo->createItems($order, $orderItems);

            foreach ($orderItems as $oi) {
                $this->productRepo->decrementStock($products->get($oi['product_id']), $oi['quantity']);
            }

            $order->update(['status' => 'completed']);

            // Event::dispatch(new OrderPlaced($order->id))->afterCommit();
            event(new OrderPlaced($order->id));

            return $order;
        });
    }
}