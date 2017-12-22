<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllergyMenuItemTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allergy_menu_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_item_id')->unsigned()->index();
            $table->integer('allergy_id')->unsigned()->index();

            $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
            $table->foreign('allergy_id')->references('id')->on('allergies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('allergy_menu_item');
    }

}
