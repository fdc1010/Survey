<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationCoordinatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_coordinates', function (Blueprint $table) {
            $table->increments('id');
			$table->enum('shape',['polygon','circle','rectangle','polyline'])->nullable();
			$table->unsignedInteger('location_id');
			$table->decimal('coordinate',10,2)->nullable();
			$table->string('name')->nullable();
			$table->longText('description')->nullable();
            $table->timestamps();
			
			$table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_coordinates');
    }
}
