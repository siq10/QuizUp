<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id');
            $table->integer('category_id');
            $table->integer('question_id');
            $table->integer('right_id');
            $table->integer('bad1_id');
            $table->integer('bad2_id')->nullable();
            $table->integer('bad3_id')->nullable();
            $table->integer('answers');
            $table->integer('mistakes');
            $table->integer('checked')->default(0);
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
        Schema::dropIfExists('problems');
    }
}
