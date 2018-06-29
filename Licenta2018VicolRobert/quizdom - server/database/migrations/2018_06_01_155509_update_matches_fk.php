<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMatchesFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {

            $table->index('user_id1');
            $table->index('user_id2');
            $table->index('category_id');

            $table->foreign('user_id1')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('user_id2')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(["user_id1"]);
            $table->dropForeign(["user_id2"]);
            $table->dropForeign(["category_id"]);
        });
    }
}
