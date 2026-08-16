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
    Schema::create('sample_requests', function (Blueprint $table) {
        $table->id();

        // Who the request belongs to
        $table->foreignId('patient_id')
            ->constrained('users')
            ->cascadeOnDelete();

        // Who created the request:
        // patient themselves OR receptionist
        $table->foreignId('requested_by')
            ->constrained('users')
            ->cascadeOnDelete();

        // Will connect to the actual blood sample
        $table->foreignId('blood_sample_id')
            ->nullable()
            ->constrained('blood_samples')
            ->nullOnDelete();

        $table->string('sample_type');
        $table->string('blood_type')->nullable();

        // Request status, NOT blood sample quality status
        $table->string('status')->default('pending');

        // Admin who approved/declined it
        $table->foreignId('approved_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamp('approved_at')->nullable();

        $table->text('notes')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_requests');
    }
};
