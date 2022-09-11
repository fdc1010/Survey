<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDuplicateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duplicate_surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
      			$table->unsignedInteger('survey_detail_id');
            $table->unsignedInteger('voter_id');
            $table->unsignedInteger('barangay_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
			      $table->foreign('survey_detail_id')->references('id')->on('survey_details');
            $table->foreign('voter_id')->references('id')->on('voters');
            $table->foreign('barangay_id')->references('id')->on('barangays');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('duplicate_surveys');
    }
}
