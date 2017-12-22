<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomMenuMenuGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_menu_menu_group', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('custom_menu_id');
            $table->integer('menu_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_menu_menu_group', function (Blueprint $table) {

            Schema::dropIfExists('custom_menu_menu_group');
        });
    }
}
