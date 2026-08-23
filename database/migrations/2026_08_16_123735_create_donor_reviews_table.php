<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('donor_reviews', function (Blueprint $table) {

            $table->id();


            // Donor user
            $table->foreignId('donor_id')
                  ->constrained('users')
                  ->onDelete('cascade');


            // Doctor user
            $table->foreignId('doctor_id')
                  ->constrained('users')
                  ->onDelete('cascade');


            // Review result
            $table->enum('medical_status', [
                'pending',
                'eligible',
                'not_eligible'
            ])->default('pending');


            // Doctor comments
            $table->text('doctor_note')
                  ->nullable();


            // When doctor reviewed
            $table->timestamp('reviewed_at')
                  ->nullable();


            $table->timestamps();

        });
    }



    public function down(): void
    {
        Schema::dropIfExists('donor_reviews');
    }

};