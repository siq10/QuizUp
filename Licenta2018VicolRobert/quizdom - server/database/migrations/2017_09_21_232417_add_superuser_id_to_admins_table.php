<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSuperuserIdToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("admins", function (Blueprint $table) {
            $table->unsignedInteger("superuser_id");
            $table->foreign("superuser_id")->references("id")->on("superuser")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("admins", function (Blueprint $table) {
            $table->dropForeign(["superuser_id"]);
            $table->dropColumn("superuser_id");
        });
    }
}
