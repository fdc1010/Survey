<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForcandidatevotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_options', function (Blueprint $table) {
            $table->boolean('for_candidate_votes')->default(0);
			$table->longText('positions')->nullable();
			$table->integer('candidate_id')->nullable();
			$table->boolean('for_issues')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_options', function (Blueprint $table) {
            $table->dropColumn('for_candidate_votes');
			$table->dropColumn('positions');
			$table->dropColumn('candidate_id');
			$table->dropColumn('for_issues');
        });
    }
}
