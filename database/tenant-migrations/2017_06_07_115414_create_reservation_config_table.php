<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_configurations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('reservation_id')->unsigned()->index();
            $table->boolean('preorders_enabled')->default(true);
            $table->boolean('display_images')->default(true);
            $table->boolean('display_prices')->default(true);
            $table->integer('hours_limit')->default(48);
            $table->integer('custom_menu_id')->unsigned()->index()->nullable()->default(NULL);
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->foreign('custom_menu_id')->references('id')->on('custom_menus')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reservation_configurations');
    }
}
