<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_problems', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('option_id');
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
        Schema::dropIfExists('option_problems');
    }
}
