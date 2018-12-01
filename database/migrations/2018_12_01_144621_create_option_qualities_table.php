<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionQualitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_qualities', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('option_id')->unique();
			$table->longText('position_id')->nullable();
			$table->longText('description')->nullable();
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
        Schema::dropIfExists('option_qualities');
    }
}
