<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMenusTitleToMenuConfig extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_config', function ($table) {
            $table->string('menus_title')->nullable()->default('Menus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_config', function ($table) {
            $table->dropColumn('menus_title');
        });
    }

}
