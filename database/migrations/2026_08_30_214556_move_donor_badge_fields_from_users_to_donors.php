<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Move the donor badge fields from users to donors.
         */
        Schema::table('donors', function (Blueprint $table) {
            $table->unsignedInteger('donation_count')
                ->default(0)
                ->after('is_verified');

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

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'donation_count',
                'donor_badge',
                'shop_discount',
                'donor_priority',
            ]);
        });
    }

    public function down(): void
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

        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn([
                'donation_count',
                'donor_badge',
                'shop_discount',
                'donor_priority',
            ]);
        });
    }
};