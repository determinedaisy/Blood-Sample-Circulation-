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
            if (!Schema::hasColumn('blood_samples', 'sample_code')) {
                $table->string('sample_code')->nullable()->unique();
            }

            // Patient who owns the sample
            if (!Schema::hasColumn('blood_samples', 'patient_id')) {
                $table->foreignId('patient_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            // User / sample collector who collected the sample
            if (!Schema::hasColumn('blood_samples', 'collected_by')) {
                $table->foreignId('collected_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            // Example: Whole Blood, Serum, Plasma
            if (!Schema::hasColumn('blood_samples', 'sample_type')) {
                $table->string('sample_type')->nullable();
            }

            // Current sample status
            if (!Schema::hasColumn('blood_samples', 'status')) {
                $table->string('status')
                    ->default('collected')
                    ->index();
            }

            // When the sample was collected
            if (!Schema::hasColumn('blood_samples', 'collected_at')) {
                $table->timestamp('collected_at')->nullable();
            }

            // Quality criteria checked by laboratory staff
            if (!Schema::hasColumn('blood_samples', 'quality_checks')) {
                $table->json('quality_checks')->nullable();
            }

            // Reason if the sample is rejected
            if (!Schema::hasColumn('blood_samples', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }

            // Lab staff member who accepted/rejected the sample
            if (!Schema::hasColumn('blood_samples', 'reviewed_by')) {
                $table->foreignId('reviewed_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            // When the sample was reviewed
            if (!Schema::hasColumn('blood_samples', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_samples', function (Blueprint $table) {

            if (Schema::hasColumn('blood_samples', 'patient_id')) {
                $table->dropForeign(['patient_id']);
            }

            if (Schema::hasColumn('blood_samples', 'collected_by')) {
                $table->dropForeign(['collected_by']);
            }

            if (Schema::hasColumn('blood_samples', 'reviewed_by')) {
                $table->dropForeign(['reviewed_by']);
            }

            $columns = [
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
            ];

            $existingColumns = [];

            foreach ($columns as $column) {
                if (Schema::hasColumn('blood_samples', $column)) {
                    $existingColumns[] = $column;
                }
            }

            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};