<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sample_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_sample_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->longText('doctor_notes')->nullable();
            $table->longText('patient_explanation')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('attachment_original_name')->nullable();
            $table->boolean('ai_generated')->default(false);
            $table->text('ai_error')->nullable();
            $table->timestamps();
        });

        Schema::create('sample_report_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_report_id')->constrained()->cascadeOnDelete();
            $table->string('test_name');
            $table->string('result_value');
            $table->string('unit')->nullable();
            $table->string('reference_range')->nullable();
            $table->string('flag')->default('normal');
            $table->text('notes')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sample_report_results');
        Schema::dropIfExists('sample_reports');
    }
};
