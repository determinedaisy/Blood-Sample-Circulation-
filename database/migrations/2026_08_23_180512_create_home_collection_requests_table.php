<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_collection_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sample_request_id')
                ->constrained('sample_requests')
                ->cascadeOnDelete();

            $table->foreignId('patient_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('assigned_collector_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('address');

            $table->decimal('latitude', 10, 7)
                ->nullable();

            $table->decimal('longitude', 10, 7)
                ->nullable();

            $table->date('preferred_date');

            $table->string('preferred_time');

            $table->text('instructions')
                ->nullable();

            $table->string('status')
                ->default('pending');

            $table->timestamp('assigned_at')
                ->nullable();

            $table->timestamp('on_the_way_at')
                ->nullable();

            $table->timestamp('arrived_at')
                ->nullable();

            $table->timestamp('collected_at')
                ->nullable();

            $table->timestamps();

            /*
             * One sample request should only have
             * one home collection appointment.
             */
            $table->unique('sample_request_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_collection_requests');
    }
};