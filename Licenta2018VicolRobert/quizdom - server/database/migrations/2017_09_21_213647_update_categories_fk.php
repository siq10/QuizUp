<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCategoriesFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("categories", function (Blueprint $table) {
            $table->integer("best_id")->unsigned()->change();
            $table->foreign("best_id")->references("id")->on("users");
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("categories", function(Blueprint $table) {
            $table->dropForeign(["best_id"]);
            $table->integer("best_id")->change();
        });
    }
}