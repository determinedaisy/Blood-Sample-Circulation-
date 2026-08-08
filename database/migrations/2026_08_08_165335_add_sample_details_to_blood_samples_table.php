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

            // Unique identification for every blood sample
            $table->string('sample_code')->nullable()->unique();

            // Patient who owns the sample
            $table->foreignId('patient_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // User / sample collector who collected the sample
            $table->foreignId('collected_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Example: Whole Blood, Serum, Plasma
            $table->string('sample_type')->nullable();

            // Current sample status
            $table->string('status')
                ->default('collected')
                ->index();

            // When the sample was collected
            $table->timestamp('collected_at')->nullable();

            // Quality criteria checked by laboratory staff
            $table->json('quality_checks')->nullable();

            // Reason if the sample is rejected
            $table->text('rejection_reason')->nullable();

            // Lab staff member who accepted/rejected the sample
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // When the sample was reviewed
            $table->timestamp('reviewed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_samples', function (Blueprint $table) {

            $table->dropForeign(['patient_id']);
            $table->dropForeign(['collected_by']);
            $table->dropForeign(['reviewed_by']);

            $table->dropColumn([
                'sample_code',
                'patient_id',
                'collected_by',
                'sample_type',
                'status',
                'collected_at',
                'quality_checks',
                'rejection_reason',
                'reviewed_by',
                'reviewed_at',
            ]);
        });
    }
};