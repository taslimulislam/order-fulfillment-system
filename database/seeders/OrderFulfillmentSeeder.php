<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐18

namespace Database\Seeders;

use App\Models\{
    Order,
    OrderItem,
    Product,
    User
};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderFulfillmentSeeder extends Seeder
{
    /**
     * Seed the order fulfillment system with buyers, sellers, products, orders, and order items.
     */
    public function run(): void
    {
        // Create sellers and buyers
        $sellers = User::factory()->count(5)->create(['role' => 'seller']);
        $buyers = User::factory()->count(5)->create(['role' => 'buyer']);

        // Create products assigned to sellers
        $products = collect();
        foreach ($sellers as $seller) {
            $products = $products->merge(
                Product::factory()->count(4)->create([
                    'seller_id' => $seller->id,
                    'stock_quantity' => rand(10, 100),
                ])
            );
        }

        // Create orders for each buyer
        foreach ($buyers as $buyer) {
            for ($i = 0; $i < 3; $i++) {
                // Ensure unique order number
                do {
                    $orderNumber = sprintf(
                        'ORD-%s-%s',
                        now()->format('Ymd'),
                        Str::upper(Str::random(6))
                    );
                } while (Order::where('order_number', $orderNumber)->exists());

                $order = Order::create([
                    'buyer_id' => $buyer->id,
                    'order_number' => $orderNumber,
                    'status' => 'paid',
                    'total_amount' => 0,
                ]);

                $total = 0;

                foreach ($products->random(3) as $product) {
                    $quantity = rand(1, 5);
                    $unitPrice = $product->price;
                    $lineTotal = $quantity * $unitPrice;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'seller_id' => $product->seller_id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'line_total' => $lineTotal,
                    ]);

                    $total += $lineTotal;
                }

                $order->update(['total_amount' => $total]);
            }
        }

    }


}
