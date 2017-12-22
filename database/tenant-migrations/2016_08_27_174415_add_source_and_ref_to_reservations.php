<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceAndRefToReservations extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function ($table) {
            $table->string('source', 75)->nullable()->default(NULL);
            $table->string('ref', 75)->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function ($table) {
            $table->dropColumn('source');
            $table->dropColumn('ref');
        });
    }

}
