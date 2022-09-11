<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOtherinfouserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voters', function (Blueprint $table) {
			$table->integer('employment_status_id')->nullable();
			$table->integer('civil_status_id')->nullable();
			$table->integer('occupancy_status_id')->nullable();
			$table->decimal('occupancy_length',10,2)->nullable();
			$table->decimal('monthly_household',10,2)->nullable();
			$table->decimal('yearly_household',10,2)->nullable();
            $table->string('work')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->dropColumn('employment_status_id');
			$table->dropColumn('civil_status_id');
			$table->dropColumn('occupancy_status_id');
			$table->dropColumn('occupancy_length');
			$table->dropColumn('monthly_household');
			$table->dropColumn('yearly_household');
			$table->dropColumn('work');
			
        });
    }
}
