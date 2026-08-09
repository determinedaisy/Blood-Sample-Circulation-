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
        Schema::create('inventories', function (Blueprint $table) {
            // Creates an auto-incrementing ID for the inventory record
            $table->id(); 
            
            // Creates a column to link this record to a specific blood sample
            $table->foreignId('blood_sample_id')->constrained()->onDelete('cascade');
            
            // Creates text columns for your required physical storage fields
            $table->string('refrigerator');
            $table->string('shelf');
            $table->string('rack');
            $table->string('storage_location');
            
            // Creates 'created_at' and 'updated_at' columns automatically
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};