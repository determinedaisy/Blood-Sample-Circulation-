<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sample_reports', function (Blueprint $table) {
            $table->foreignId('lab_submitted_by')
                ->nullable()
                ->after('doctor_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('lab_submitted_at')->nullable()->after('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('sample_reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('lab_submitted_by');
            $table->dropColumn('lab_submitted_at');
        });
    }
};
