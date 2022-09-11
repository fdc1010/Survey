<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnsweredOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answered_options', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('survey_answer_id');
			$table->integer('option_id')->nullable();
            $table->timestamps();
			
			$table->foreign('survey_answer_id')->references('id')->on('survey_answers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answered_options');
    }
}
