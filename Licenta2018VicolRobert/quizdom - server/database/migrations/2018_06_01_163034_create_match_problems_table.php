<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_problems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('match_id')->unsigned();
            $table->integer('problem_id')->unsigned()->nullable();

            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->foreign('problem_id')->references('id')->on('problems')->onDelete('cascade');

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
        Schema::dropIfExists('match_problems');
    }
}
