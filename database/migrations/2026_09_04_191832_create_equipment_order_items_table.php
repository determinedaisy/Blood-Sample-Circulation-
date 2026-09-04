<?php

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
        Schema::create('equipment_order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('equipment_order_id')
                ->constrained('equipment_orders')
                ->onDelete('cascade');

            $table->foreignId('equipment_id')
                ->constrained('equipment')
                ->onDelete('cascade');

            $table->unsignedInteger('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_order_items');
    }
};