<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgeReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('age_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('voter_id');
            $table->integer('age');
            $table->timestamps();

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
        Schema::dropIfExists('age_reviews');
    }
}
