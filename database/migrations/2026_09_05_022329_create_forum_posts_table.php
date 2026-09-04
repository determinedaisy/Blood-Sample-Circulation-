<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->after('id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('title')->after('user_id');

            $table->text('content')->after('title');

            $table->string('image')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'title',
                'content',
                'image',
            ]);
        });
    }
};