<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrgyIdSitioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->integer('municipality_id')->nullable();
			$table->integer('barangay_id')->nullable();
			$table->integer('sitio_id')->nullable();
        });
		
		Schema::table('sitios', function (Blueprint $table) {            
			$table->integer('barangay_id')->nullable();
        });
		
		Schema::table('voters', function (Blueprint $table) {            
			$table->integer('sitio_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sitios', function (Blueprint $table) {
			$table->dropColumn('barangay_id');
        });
		Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('municipality_id');
			$table->dropColumn('barangay_id');
			$table->dropColumn('sitio_id');
        });
		Schema::table('voters', function (Blueprint $table) {
            $table->dropColumn('sitio_id');
        });
    }
}
