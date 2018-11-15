<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
			$table->longText('question');			
			$table->integer('number_answers')->default(1);
			$table->integer('priority')->default(0);
			$table->integer('type_id')->default(1);
			$table->integer('for_position')->nullable();
			$table->boolean('is_visible')->default(1);
			$table->boolean('with_other_ans')->default(1);		
			$table->boolean('with_partyselect')->default(0);
			$table->string('options')->nullable();
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
        Schema::dropIfExists('questions');
    }
}
