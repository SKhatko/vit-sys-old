<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateEventTypesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert("insert into event_types (`id`, `name`) VALUES (1, 'none')");
        DB::insert("insert into event_types (`id`, `name`) VALUES (2, 'birthday')");
        DB::insert("insert into event_types (`id`, `name`) VALUES (3, 'wedding')");
        DB::insert("insert into event_types (`id`, `name`) VALUES (4, 'wedding_anniversary')");
        DB::insert("insert into event_types (`id`, `name`) VALUES (5, 'graduation_party')");
        DB::insert("insert into event_types (`id`, `name`) VALUES (6, 'company_event')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('event_types')->truncate();
    }

}
