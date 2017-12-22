<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemColumnsToMenuConfig extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_config', function ($table) {

            $defaultFont = "Open Sans";

            $table->integer('item_name_font_size')->nullable()->default(16);
            $table->string('item_name_font_color', 35)->nullable()->default('#000000');
            $table->string('item_name_font')->nullable()->default($defaultFont);

            $table->integer('item_description_font_size')->nullable()->default(16);
            $table->string('item_description_font_color', 35)->nullable()->default('#666666');
            $table->string('item_description_font')->nullable()->default($defaultFont);

            $table->integer('item_price_font_size')->nullable()->default(16);
            $table->string('item_price_font_color', 35)->nullable()->default('#000000');
            $table->string('item_price_font')->nullable()->default($defaultFont);
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
            $table->dropColumn('item_name_font_size');
            $table->dropColumn('item_name_font_color');
            $table->dropColumn('item_name_font');

            $table->dropColumn('item_description_font_size');
            $table->dropColumn('item_description_font_color');
            $table->dropColumn('item_description_font');

            $table->dropColumn('item_price_font_size');
            $table->dropColumn('item_price_font_color');
            $table->dropColumn('item_price_font');
        });
    }

}
