<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatlngsurveyansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_answers', function (Blueprint $table) {
            $table->decimal('latitude',10,4)->nullable();
			$table->decimal('longitude',10,4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_answers', function (Blueprint $table) {
            $table->dropColumn('latitude');
			$table->dropColumn('longitude');
        });
    }
}
