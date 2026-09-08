<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('blood_samples', function (Blueprint $table) {
            if (!Schema::hasColumn('blood_samples', 'is_blacklisted')) {
                $table->boolean('is_blacklisted')->default(false);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_blacklisted')) {
                $table->boolean('is_blacklisted')->default(false);
            }
            if (!Schema::hasColumn('users', 'deferral_reason')) {
                $table->string('deferral_reason')->nullable();
            }
            if (!Schema::hasColumn('users', 'medical_history')) {
                $table->text('medical_history')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('blood_samples', function (Blueprint $table) {
            $table->dropColumn('is_blacklisted');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_blacklisted', 'deferral_reason', 'medical_history']);
        });
    }
};