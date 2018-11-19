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
            $table->integer('area_id')->nullable();
        })
		
		Schema::table('sitios', function (Blueprint $table) {            
			$table->integer('barangay_id')->nullable();
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
            $table->dropColumn('area_id');
			$table->dropColumn('barangay_id');
        });
    }
}
