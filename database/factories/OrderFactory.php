<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐18

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => strtoupper(Str::random(10)),
            'buyer_id' => 1, // will be overridden in seeder
            'status' => 'paid',
            'total_amount' => 0, // calculated later
        ];

    }
}
