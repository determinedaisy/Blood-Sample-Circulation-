<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_comments', function (Blueprint $table) {
            $table->foreignId('forum_post_id')
                ->after('id')
                ->constrained('forum_posts')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->after('forum_post_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('content')
                ->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('forum_comments', function (Blueprint $table) {
            $table->dropForeign(['forum_post_id']);
            $table->dropForeign(['user_id']);

            $table->dropColumn([
                'forum_post_id',
                'user_id',
                'content',
            ]);
        });
    }
};
