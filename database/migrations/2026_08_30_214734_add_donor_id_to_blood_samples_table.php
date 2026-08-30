<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blood_samples', function (Blueprint $table) {
            $table->foreignId('donor_id')
                ->nullable()
                ->after('patient_id')
                ->constrained('donors')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('blood_samples', function (Blueprint $table) {
            $table->dropForeign(['donor_id']);
            $table->dropColumn('donor_id');
        });
    }
};