<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pharmacy_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('delivery_address');

            $table->enum('status', [
                'pending',
                'accepted'
            ])->default('pending');

            $table->decimal('total_amount', 10, 2)
                ->default(0);

            $table->foreignId('accepted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('accepted_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_orders');
    }
};