<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_positions', function (Blueprint $table) {
			$table->unsignedInteger('option_id')->index();
			$table->unsignedInteger('position_id')->index();
            
			$table->foreign('position_id')->references('id')->on('position_candidates');
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
        Schema::dropIfExists('option_positions');
    }
}
