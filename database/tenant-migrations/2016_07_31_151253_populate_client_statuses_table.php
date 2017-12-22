<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateClientStatusesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert("insert into client_statuses (`id`, `name`) VALUES (1, 'normal')");
        DB::insert("insert into client_statuses (`id`, `name`) VALUES (2, 'vip')");
        DB::insert("insert into client_statuses (`id`, `name`) VALUES (3, 'king')");
        DB::insert("insert into client_statuses (`id`, `name`) VALUES (4, 'blacklist')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('client_statuses')->truncate();
    }

}
