<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voters', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('precinct_id');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('middle_name')->nullable();
			$table->dateTime('birth_date')->nullable();
			$table->string('contact')->nullable();
			$table->string('address')->nullable();
			$table->string('birth_place')->nullable();
			$table->decimal('age',10,2)->nullable();
			$table->enum('gender', ['M', 'F']);
			$table->longText('profilepic')->nullable();
			$table->string('baranggay');
			$table->integer('status_id')->nullable();
			$table->integer('seq_num')->nullable();
            $table->timestamps();
			
			$table->foreign('precinct_id')->references('id')->on('precincts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voters');
    }
}
