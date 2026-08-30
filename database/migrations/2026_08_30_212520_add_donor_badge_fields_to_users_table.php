<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('donation_count')
                ->default(0)
                ->after('role');

            $table->string('donor_badge')
                ->default('none')
                ->after('donation_count');

            $table->unsignedInteger('shop_discount')
                ->default(0)
                ->after('donor_badge');

            $table->unsignedInteger('donor_priority')
                ->default(0)
                ->after('shop_discount');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'donation_count',
                'donor_badge',
                'shop_discount',
                'donor_priority',
            ]);
        });
    }
};