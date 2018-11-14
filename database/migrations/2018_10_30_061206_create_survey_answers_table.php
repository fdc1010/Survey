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
			$table->unsignedInteger('survey_id');
			$table->integer('option_id')->nullable();
			$table->integer('answered_option')->nullable();
			$table->string('other_answer')->nullable();
            $table->timestamps();
			
			$table->foreign('question_id')->references('id')->on('questions');
			$table->foreign('survey_id')->references('id')->on('surveys');
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
