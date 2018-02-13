<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListAccTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_acc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id');
            $table->integer('shop_id');
            $table->integer('status')->default(0);
            $table->string('username');
            $table->string('password');
            $table->text('description')->nullable();
            $table->text('price');
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
        Schema::dropIfExists('list_acc');
    }
}
