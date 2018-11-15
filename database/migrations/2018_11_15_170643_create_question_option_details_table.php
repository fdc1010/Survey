<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionOptionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_option_details', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('question_id');
			$table->unsignedInteger('option_id');
			$table->unsignedInteger('sub_option_id');
			$table->longText('description')->nullable();
            $table->timestamps();
			
			$table->foreign('question_id')->references('id')->on('questions');
			$table->foreign('option_id')->references('id')->on('question_options');
			$table->foreign('sub_option_id')->references('id')->on('question_sub_options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_option_details');
    }
}
