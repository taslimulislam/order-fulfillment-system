<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐18

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id'   => 1, // overridden
            'product_id' => 1, // overridden
            'seller_id'  => 1, // overridden
            'quantity'   => $this->faker->numberBetween(1, 5),
            'unit_price' => 0, // overridden
            'line_total' => 0, // calculated
        ];


    }
}
