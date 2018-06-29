<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserProblemsFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("user_problems", function(Blueprint $table) {
            $table->integer("user_id")->unsigned()->change();
            $table->integer("problem_id")->unsigned()->change();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("problem_id")->references("id")->on("problems")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("user_problems", function(Blueprint $table) {
            $table->dropForeign(["user_id"]);
            $table->dropForeign(["problem_id"]);
            $table->integer("user_id")->change();
            $table->integer("problem_id")->change();
        });
    }
}
