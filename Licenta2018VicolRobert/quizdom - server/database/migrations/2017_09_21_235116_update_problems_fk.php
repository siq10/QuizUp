<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProblemsFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("problems", function(Blueprint $table){
            $table->integer("owner_id")->unsigned()->change();
            $table->foreign("owner_id")->references("id")->on("superuser");
            
            $table->integer("category_id")->unsigned()->change();
            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade");
            
            $table->integer("question_id")->unsigned()->change();
            $table->foreign("question_id")->references("id")->on("questions");
            
            $table->integer("right_id")->unsigned()->change();
            $table->foreign("right_id")->references("id")->on("answers");
            
            $table->integer("bad1_id")->unsigned()->change();
            $table->foreign("bad1_id")->references("id")->on("answers");

            $table->integer("bad2_id")->unsigned()->change();
            $table->foreign("bad2_id")->references("id")->on("answers");
            
            $table->integer("bad3_id")->unsigned()->change();
            $table->foreign("bad3_id")->references("id")->on("answers");            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("problems", function(Blueprint $table){
            $table->dropForeign("owner_id");
            $table->integer("owner_id")->change();

            $table->dropForeign("category_id");
            $table->integer("category_id")->change();
            
            $table->dropForeign("question_id");
            $table->integer("question_id")->change();
            
            $table->dropForeign("right_id");
            $table->integer("right_id")->change();
            
            $table->dropForeign("bad1_id");
            $table->integer("bad1_id")->change();

            $table->dropForeign("bad2_id");
            $table->integer("bad2_id")->change();
            
            $table->dropForeign("bad3_id");            
            $table->integer("bad3_id")->change();
        });
    }
}
