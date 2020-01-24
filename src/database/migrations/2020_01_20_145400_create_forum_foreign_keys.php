<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('forum_discussions', function (Blueprint $table) {
            $table->foreign('forum_category_id')->references('id')->on('forum_categories')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->foreign('forum_discussion_id')->references('id')->on('forum_discussions')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('forum_discussion', function (Blueprint $table) {
            $table->dropForeign(['forum_category_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::table('forum_post', function (Blueprint $table) {
            $table->dropForeign(['forum_discussion_id']);
            $table->dropForeign(['user_id']);
        });
    }
}
