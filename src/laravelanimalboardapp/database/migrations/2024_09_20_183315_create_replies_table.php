<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('board_id');
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
            // 外部キーを設定する
            $table->foreign('board_id')->references('id')->on('replies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
}
