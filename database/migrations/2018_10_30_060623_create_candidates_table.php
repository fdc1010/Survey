<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->increments('id');
      			$table->unsignedInteger('position_id');
      			$table->integer('party_id')->nullable();
      			$table->unsignedInteger('voter_id');
            $table->integer('priority')->default(1);
            $table->timestamps();

			$table->foreign('position_id')->references('id')->on('position_candidates');
			$table->foreign('voter_id')->references('id')->on('voters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidates');
    }
}
