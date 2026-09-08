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
        Schema::table('blood_samples', function (Blueprint $table) {
            $table->foreignId('assigned_lab_staff_id')
                ->nullable()
                ->after('collected_by')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_samples', function (Blueprint $table) {
            $table->dropForeign(['assigned_lab_staff_id']);
            $table->dropColumn('assigned_lab_staff_id');
        });
    }
};