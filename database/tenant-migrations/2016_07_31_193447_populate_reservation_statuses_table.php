<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateReservationStatusesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert("insert into reservation_statuses (`id`, `name`, `show_on_creation`) VALUES (1, 'active', true)");
        DB::insert("insert into reservation_statuses (`id`, `name`, `show_on_creation`) VALUES (2, 'cancelled', false)");
        DB::insert("insert into reservation_statuses (`id`, `name`, `show_on_creation`) VALUES (3, 'noshow', false)");
        DB::insert("insert into reservation_statuses (`id`, `name`, `show_on_creation`) VALUES (4, 'waiting', true)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('reservation_statuses')->truncate();
    }

}
