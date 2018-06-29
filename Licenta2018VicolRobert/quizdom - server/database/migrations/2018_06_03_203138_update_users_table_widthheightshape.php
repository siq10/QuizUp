<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTableWidthheightshape extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer("imgwidth")->default(0);
            $table->integer("imgheight")->default(0);
            $table->char("imgshape",32)->default("polygon");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("imgwidth");
            $table->dropColumn("imgshape");
            $table->dropColumn("imgheight");

        });
    }
}
