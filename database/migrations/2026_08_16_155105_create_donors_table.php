<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donors', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('blood_group');

            $table->string('phone');

            $table->decimal('latitude', 10, 7)
                  ->nullable();

            $table->decimal('longitude', 10, 7)
                  ->nullable();

            $table->boolean('is_willing')
                  ->default(false);

            $table->boolean('is_available')
                  ->default(false);

            $table->boolean('is_verified')
                  ->default(false);

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};