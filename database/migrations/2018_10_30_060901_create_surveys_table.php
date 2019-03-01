<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('voter_id');
			$table->unsignedInteger('survey_detail_id');			
			$table->boolean('is_done')->default(0);			
			$table->dateTime('call_date')->nullable();
			$table->boolean('had_called')->default(0);
			$table->integer('call_times')->default(0);
			$table->boolean('is_sync')->default(0);
			$table->dateTime('sync_date')->nullable();
			$table->integer('sync_times')->default(0);
			$table->decimal('progress',10,2)->default(0);
            $table->timestamps();
			
			$table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('surveys');
    }
}
