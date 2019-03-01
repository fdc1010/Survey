<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElectionReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('election_returns', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('election_id');
			$table->unsignedInteger('candidate_id');
			$table->integer('voter_id')->nullable();
			$table->integer('precinct_id')->nullable();
			$table->integer('tally')->default(1);
			$table->string('subject')->nullable();
			$table->longText('description')->nullable();
            $table->timestamps();
			
			$table->foreign('election_id')->references('id')->on('elections');
			$table->foreign('candidate_id')->references('id')->on('candidates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('election_returns');
    }
}
