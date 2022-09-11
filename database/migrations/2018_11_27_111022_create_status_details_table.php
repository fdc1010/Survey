<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_details', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('status_id');
			$table->unsignedInteger('voter_id');
			$table->string('extras');
            $table->timestamps();
			
			$table->foreign('status_id')->references('id')->on('voter_statuses');
			$table->foreign('voter_id')->references('id')->on('voters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_details');
    }
}
