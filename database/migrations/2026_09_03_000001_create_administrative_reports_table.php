<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrative_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('report_type')->index();
            $table->date('start_date');
            $table->date('end_date');
            $table->json('metrics');
            $table->longText('ai_summary')->nullable();
            $table->boolean('ai_generated')->default(false);
            $table->text('ai_error')->nullable();
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrative_reports');
    }
};
