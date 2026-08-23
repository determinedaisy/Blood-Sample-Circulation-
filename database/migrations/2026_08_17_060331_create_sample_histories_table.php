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
        Schema::create('sample_histories', function (Blueprint $table) {
            $table->id();
            
            // Links to the existing blood_samples and users tables
            $table->foreignId('blood_sample_id')->constrained('blood_samples')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Core tracking columns
            $table->string('action'); 
            $table->text('description')->nullable(); 
            $table->string('location')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_histories');
    }
};