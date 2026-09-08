<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emergency_requests', function (Blueprint $table) {

            if (!Schema::hasColumn(
                'emergency_requests',
                'priority'
            )) {
                $table->string('priority')
                    ->nullable()
                    ->after('status');
            }

            if (!Schema::hasColumn(
                'emergency_requests',
                'priority_reason'
            )) {
                $table->text('priority_reason')
                    ->nullable()
                    ->after('priority');
            }
        });

        Schema::table('donor_requests', function (Blueprint $table) {

            if (!Schema::hasColumn(
                'donor_requests',
                'emergency_request_id'
            )) {
                $table->foreignId('emergency_request_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('emergency_requests')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn(
                'donor_requests',
                'patient_latitude'
            )) {
                $table->decimal('patient_latitude', 10, 7)
                    ->nullable()
                    ->after('status');
            }

            if (!Schema::hasColumn(
                'donor_requests',
                'patient_longitude'
            )) {
                $table->decimal('patient_longitude', 10, 7)
                    ->nullable()
                    ->after('patient_latitude');
            }

            if (!Schema::hasColumn(
                'donor_requests',
                'donor_latitude'
            )) {
                $table->decimal('donor_latitude', 10, 7)
                    ->nullable()
                    ->after('patient_longitude');
            }

            if (!Schema::hasColumn(
                'donor_requests',
                'donor_longitude'
            )) {
                $table->decimal('donor_longitude', 10, 7)
                    ->nullable()
                    ->after('donor_latitude');
            }

            if (!Schema::hasColumn(
                'donor_requests',
                'accepted_at'
            )) {
                $table->timestamp('accepted_at')
                    ->nullable()
                    ->after('donor_longitude');
            }

            if (!Schema::hasColumn(
                'donor_requests',
                'completed_at'
            )) {
                $table->timestamp('completed_at')
                    ->nullable()
                    ->after('accepted_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donor_requests', function (Blueprint $table) {

            if (Schema::hasColumn(
                'donor_requests',
                'emergency_request_id'
            )) {
                $table->dropForeign([
                    'emergency_request_id'
                ]);

                $table->dropColumn(
                    'emergency_request_id'
                );
            }

            $columns = [
                'patient_latitude',
                'patient_longitude',
                'donor_latitude',
                'donor_longitude',
                'accepted_at',
                'completed_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn(
                    'donor_requests',
                    $column
                )) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('emergency_requests', function (Blueprint $table) {

            if (Schema::hasColumn(
                'emergency_requests',
                'priority_reason'
            )) {
                $table->dropColumn('priority_reason');
            }

            if (Schema::hasColumn(
                'emergency_requests',
                'priority'
            )) {
                $table->dropColumn('priority');
            }
        });
    }
};