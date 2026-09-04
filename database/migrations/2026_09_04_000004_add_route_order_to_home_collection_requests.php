<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_collection_requests', function (Blueprint $table) {
            $table->unsignedInteger('route_order')
                ->nullable()
                ->after('preferred_time');

            $table->index(
                ['preferred_date', 'assigned_collector_id', 'route_order'],
                'home_collection_daily_route_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('home_collection_requests', function (Blueprint $table) {
            $table->dropIndex('home_collection_daily_route_index');
            $table->dropColumn('route_order');
        });
    }
};
