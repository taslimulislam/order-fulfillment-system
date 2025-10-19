<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐17

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users'); //->cascadeOnDelete()
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->enum('status', ['pending','processing','paid','cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
