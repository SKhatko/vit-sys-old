<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateOfferStatusesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert("insert into offer_statuses (`id`, `name`) VALUES (1, 'none')");
        DB::insert("insert into offer_statuses (`id`, `name`) VALUES (2, 'requested')");
        DB::insert("insert into offer_statuses (`id`, `name`) VALUES (3, 'sent')");
        DB::insert("insert into offer_statuses (`id`, `name`) VALUES (4, 'rejected')");
        DB::insert("insert into offer_statuses (`id`, `name`) VALUES (5, 'confirmed')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('offer_statuses')->truncate();
    }

}
