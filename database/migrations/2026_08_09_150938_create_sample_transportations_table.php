<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sample_transportations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('blood_sample_id')
                ->constrained('blood_samples')
                ->cascadeOnDelete();

            $table->foreignId('collection_center_id')
                ->constrained('collection_centers');

            $table->foreignId('laboratory_id')
                ->constrained('laboratories');

            $table->foreignId('transported_by')
                ->constrained('users');

            $table->string('status')->default('pending');

            $table->timestamp('departure_time')->nullable();
            $table->timestamp('arrival_time')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sample_transportations');
    }
};