<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reception_requests', function (Blueprint $table) {
            $table->id();

            // Patient who contacted reception
            $table->foreignId('patient_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Details supplied by patient
            $table->string('sample_type');
            $table->string('blood_type')->nullable();
            $table->text('notes')->nullable();

            // Reception workflow
            $table->string('status')->default('pending');

            // Receptionist who eventually processes it
            $table->foreignId('handled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('handled_at')->nullable();

            // Once processed, connect it to the real sample request
            $table->foreignId('sample_request_id')
                ->nullable()
                ->constrained('sample_requests')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reception_requests');
    }
};