<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('preview')->nullable();
            $table->string('name', 30);
            $table->string('subject', 120);
            $table->text('message');
            $table->string('image_path', 60)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('url', 256)->nullable();
            $table->string('color', 30);
            $table->string('delete_key', 8);
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
        Schema::dropIfExists('posts');
    }
}
