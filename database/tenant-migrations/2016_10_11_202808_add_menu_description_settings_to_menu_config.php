<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMenuDescriptionSettingsToMenuConfig extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_config', function ($table) {
            $table->boolean('menu_description_bold')->default(false);
            $table->boolean('menu_description_italic')->default(false);
            $table->boolean('menu_description_underlined')->default(false);

            $table->integer('menu_description_font_size')->nullable()->default(16);
            $table->string('menu_description_font_color', 35)->nullable()->default('#000000');
            $table->string('menu_description_font')->nullable()->default('Open Sans');
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
            $table->dropColumn('menu_description_bold');
            $table->dropColumn('menu_description_italic');
            $table->dropColumn('menu_description_underlined');

            $table->dropColumn('menu_description_font_size');
            $table->dropColumn('menu_description_font_color');
            $table->dropColumn('menu_description_font');
        });
    }

}
