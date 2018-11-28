<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyorAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveyor_assignments', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('survey_detail_id');
			$table->integer('quota')->nullable();
			$table->decimal('progress',10,2)->nullable();
			$table->string('task')->nullable();
			$table->longText('description')->nullable();
            $table->timestamps();
			
			$table->foreign('survey_detail_id')->references('id')->on('survey_details');
			$table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surveyor_assignments');
    }
}
