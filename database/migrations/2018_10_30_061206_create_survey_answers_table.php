<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('question_id');
			$table->unsignedInteger('voter_id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('survey_detail_id');
			$table->integer('option_id')->nullable();
			$table->integer('answered_option')->nullable();
			$table->string('other_answer')->nullable();
            $table->timestamps();
			
			$table->foreign('question_id')->references('id')->on('questions');
			$table->foreign('survey_detail_id')->references('id')->on('survey_details');
			$table->foreign('voter_id')->references('id')->on('voters');
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
        Schema::dropIfExists('survey_answers');
    }
}
