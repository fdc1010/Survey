<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOttherUserIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('duplicate_surveys', function (Blueprint $table) {
            $table->integer('other_user_id')->nullable();

            $table->foreign('other_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('duplicate_surveys', function (Blueprint $table) {
            $table->dropColumn('other_user_id');
        });
    }
}
