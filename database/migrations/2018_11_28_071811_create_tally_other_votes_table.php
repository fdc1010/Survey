<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTallyOtherVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tally_other_votes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id');
      			$table->unsignedInteger('option_id');
      			$table->unsignedInteger('voter_id');
      			$table->unsignedInteger('survey_detail_id');
            $table->unsignedInteger('user_id');
      			$table->integer('barangay_id')->nullable();
      			$table->integer('candidate_id')->nullable();
      			$table->integer('tally')->default(1);
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('questions');
      			$table->foreign('option_id')->references('id')->on('question_options');
      			$table->foreign('voter_id')->references('id')->on('voters');
      			$table->foreign('survey_detail_id')->references('id')->on('survey_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tally_other_votes');
    }
}
