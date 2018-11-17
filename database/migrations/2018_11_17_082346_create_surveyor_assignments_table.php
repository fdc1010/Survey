<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyorAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveyor_assignments', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('voter_id');
			$table->unsignedInteger('barangay_id');
			$table->decimal('quota',10,2);
			$table->decimal('progress',10,2);
			$table->string('subject')->nullable();
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
        Schema::dropIfExists('surveyor_assignments');
    }
}
