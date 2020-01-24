<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumDiscussionsTable extends Migration
{
    public function up()
    {
        Schema::create('forum_discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('forum_category_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('user_id');
            $table->boolean('sticky')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->boolean('answered')->default(0);
            $table->timestamp('last_reply_at')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('forum_discussions');
    }
}
