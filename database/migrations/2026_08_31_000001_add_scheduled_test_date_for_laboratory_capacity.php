

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('laboratories')
            ->whereNull('daily_capacity')
            ->update(['daily_capacity' => 20]);

        Schema::table('laboratories', function (Blueprint $table) {
            $table->unsignedInteger('daily_capacity')
                ->default(20)
                ->nullable(false)
                ->change();
        });

        Schema::table('sample_transportations', function (Blueprint $table) {
            $table->date('scheduled_test_date')
                ->nullable()
                ->after('laboratory_id')
                ->index();
        });

        DB::table('sample_transportations')
            ->whereNull('scheduled_test_date')
            ->update(['scheduled_test_date' => DB::raw('DATE(created_at)')]);
    }

    public function down(): void
    {
        Schema::table('sample_transportations', function (Blueprint $table) {
            $table->dropIndex(['scheduled_test_date']);
            $table->dropColumn('scheduled_test_date');
        });

        Schema::table('laboratories', function (Blueprint $table) {
            $table->unsignedInteger('daily_capacity')
                ->nullable()
                ->default(null)
                ->change();
        });
    }
};
