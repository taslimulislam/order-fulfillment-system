<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐19

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    /**
     * Initialize required repository dependencies.
     *
     * @param ProductRepository $productRepository Repository for product operations.
     * @param OrderRepository $orderRepository Repository for order operations.
     */
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly OrderRepository $orderRepository,
    ) {}

    /**
     * New order create.
     *
     * @param User $buyer authenticated buyer.
     * @param array $data Validated order data.
     * @return Order order instance.
     *
     * @throws Exception If order creation fails.
     */ 
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
            $products = $this->productRepository
                ->getProductsForOrderWithLock($productIds)
                ->keyBy('id');

            $orderItems = [];
            $total = 0.0;

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
                    throw ValidationException::withMessages(['stock' => "Insufficient stock for product: {$product->name}."]);
                }

                $unitPrice = (float) $product->price;
                $lineTotal = $unitPrice * $qty;
                $total += $lineTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'seller_id'  => $product->seller_id,
                    'quantity'   => $qty,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $order = $this->orderRepository->createOrder($buyer->id, [
                'total_amount' => $total,
            ]);

            $this->orderRepository->createItems($order, $orderItems);

            foreach ($orderItems as $item) {
                $this->productRepository->decrementStock($products->get($item['product_id']), $item['quantity']);
            }

            $order->update(['status' => 'paid']);

            event(new OrderPlaced($order->id));

            return $order;
        });
    }
}