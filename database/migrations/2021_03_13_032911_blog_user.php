<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BlogUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_users', function (Blueprint $table) {
            $table->increments('id');
            $table->String('name');
            $table->String('email')->unique();

            $table->String('password');
            $table->String('avatar');
            $table->String('quesion_count')->default(0);
            $table->String('answer_count')->default(0);
            $table->String('comment_count')->default(0);
            $table->String('fav_count')->default(0);
            $table->String('like_count')->default(0);
            $table->String('follower_count')->default(0);
            $table->String('following_count')->default(0);
            $table->json('setting')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
