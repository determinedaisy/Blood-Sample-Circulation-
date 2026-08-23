<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('donor_requests', function (Blueprint $table) {

            $table->id();


            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();


            $table->foreignId('donor_id')
                ->constrained()
                ->cascadeOnDelete();


            $table->string('status')
                ->default('pending');


            $table->timestamps();

        });

    }


    public function down(): void
    {
        Schema::dropIfExists('donor_requests');
    }

};