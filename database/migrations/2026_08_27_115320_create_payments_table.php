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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Relate the payment to the specific sample and patient
            $table->foreignId('blood_sample_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            
            // Financial details
            $table->decimal('amount', 8, 2);
            $table->string('invoice_number')->unique(); 
            
            // bKash specific tracking columns
            $table->string('bkash_payment_id')->nullable(); 
            $table->string('transaction_id')->nullable()->unique(); 
            
            // Transaction lifecycle
            $table->enum('status', ['pending', 'completed', 'failed', 'canceled'])->default('pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};