<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlacklistFields extends Migration
{
    public function up()
    {
        Schema::table('blood_samples', function (Blueprint $table) {
            $table->boolean('is_blacklisted')->default(false);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_blacklisted')->default(false);
            $table->string('deferral_reason')->nullable();
        });
    }

    public function down()
    {
        Schema::table('blood_samples', function (Blueprint $table) {
            $table->dropColumn('is_blacklisted');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_blacklisted', 'deferral_reason']);
        });
    }
}