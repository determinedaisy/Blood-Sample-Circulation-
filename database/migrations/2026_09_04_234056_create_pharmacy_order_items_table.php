<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pharmacy_order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pharmacy_order_id')
                ->constrained('pharmacy_orders')
                ->cascadeOnDelete();

            $table->foreignId('medicine_id')
                ->constrained('medicines')
                ->restrictOnDelete();

            $table->unsignedInteger('quantity');

            $table->decimal('unit_price', 10, 2);

            $table->decimal('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_order_items');
    }
};