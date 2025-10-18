<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐18

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(OrderFulfillmentSeeder::class);
    }
}
