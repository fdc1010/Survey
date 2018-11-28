<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSidtallyvotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tally_votes', function (Blueprint $table) {
            $table->unsignedInteger('survey_detail_id');
			
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
        Schema::table('tally_votes', function (Blueprint $table) {
            $table->dropColumn('survey_detail_id');
        });
    }
}
