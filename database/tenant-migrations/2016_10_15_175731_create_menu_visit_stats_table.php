<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuVisitStatsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_visit_stats', function (Blueprint $table) {
            $table->date('date');
            $table->integer('views')->default(0);
            $table->integer('visitors')->default(0);

            $table->primary('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('menu_visit_stats');
    }

}
