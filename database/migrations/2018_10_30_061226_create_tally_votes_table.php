<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTallyVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tally_votes', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('candidate_id');
			$table->integer('voter_id');
			$table->integer('tally')->default(0);
            $table->timestamps();
			
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
        Schema::dropIfExists('tally_votes');
    }
}
