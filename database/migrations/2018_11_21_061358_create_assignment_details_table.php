<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_details', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('assignment_id');
			$table->integer('barangay_id')->nullable();
			$table->integer('sitio_id')->nullable();
			$table->integer('quota')->nullable();
			$table->decimal('progress',10,2)->nullable();
			$table->string('task')->nullable();
			$table->longText('description')->nullable();
            $table->timestamps();
			
			$table->foreign('assignment_id')->references('id')->on('surveyor_assignments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignment_details');
    }
}
