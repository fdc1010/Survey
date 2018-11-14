<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionSubOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_sub_options', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('option_id');
			$table->string('sub_option')->nullable();
			$table->integer('priority')->default(0);
            $table->timestamps();
			
			$table->foreign('option_id')->references('id')->on('question_options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_sub_options');
    }
}
