<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatedQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('related_questions', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('question_id');
			$table->unsignedInteger('related_question_id');
			$table->longText('description')->nullable();
            $table->timestamps();
			
			$table->foreign('question_id')->references('id')->on('questions');
			$table->foreign('related_question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('related_questions');
    }
}
