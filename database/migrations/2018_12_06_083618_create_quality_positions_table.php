<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQualityPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quality_positions', function (Blueprint $table) {
            $table->increments('id');
      			$table->integer('option_id')->nullable();
      			$table->integer('position_id')->nullable();
      			$table->longText('options')->nullable();
      			$table->longText('positions')->nullable();
      			$table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quality_positions');
    }
}
