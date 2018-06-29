<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id1')->unsigned()->nullable();

            $table->integer('user_id2')->unsigned()->nullable();

            $table->smallInteger("winner")->nullable();

            $table->integer('category_id')->nullable()->unsigned();

            $table->integer('odds1')->nullable();
            $table->integer('odds2')->nullable();
            $table->integer('xp1')->nullable();
            $table->integer('xp2')->nullable();
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
        Schema::dropIfExists('matches');
    }
}
