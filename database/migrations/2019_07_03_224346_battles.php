<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Battles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('battle_id')->nullable();
            $table->string('channel')->nullable();
            $table->integer('views')->nullable();
            $table->integer('comments')->nullable();
            $table->integer('likes')->nullable();
            $table->integer('timestamp')->nullable();
            $table->integer('dislikes')->nullable();
            $table->float('likes_perc', null, null)->nullable();
            $table->float('dislikes_perc,' null, null)->nullable();
            $table->integer('team')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('battles');
    }
}
