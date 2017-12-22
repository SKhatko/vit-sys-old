<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlignmentAndFormattingSettingsToMenuConfig extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_config', function ($table) {
            $table->string('main_category_alignment', 15)->default('center');
            $table->string('sub_category_alignment', 15)->default('left');

            $table->boolean('navigation_bold')->default(false);
            $table->boolean('main_category_bold')->default(false);
            $table->boolean('sub_category_bold')->default(false);
            $table->boolean('sub_sub_category_bold')->default(false);
            $table->boolean('item_name_bold')->default(false);
            $table->boolean('item_description_bold')->default(false);
            $table->boolean('item_price_bold')->default(false);

            $table->boolean('navigation_italic')->default(false);
            $table->boolean('main_category_italic')->default(false);
            $table->boolean('sub_category_italic')->default(false);
            $table->boolean('sub_sub_category_italic')->default(false);
            $table->boolean('item_name_italic')->default(false);
            $table->boolean('item_description_italic')->default(false);
            $table->boolean('item_price_italic')->default(false);

            $table->boolean('main_category_underlined')->default(false);
            $table->boolean('sub_category_underlined')->default(false);
            $table->boolean('sub_sub_category_underlined')->default(false);
            $table->boolean('item_name_underlined')->default(false);
            $table->boolean('item_description_underlined')->default(false);
            $table->boolean('item_price_underlined')->default(false);
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
            $table->dropColumn('main_category_alignment');
            $table->dropColumn('sub_category_alignment');

            $table->dropColumn('navigation_bold');
            $table->dropColumn('main_category_bold');
            $table->dropColumn('sub_category_bold');
            $table->dropColumn('sub_sub_category_bold');
            $table->dropColumn('item_name_bold');
            $table->dropColumn('item_description_bold');
            $table->dropColumn('item_price_bold');

            $table->dropColumn('navigation_italic');
            $table->dropColumn('main_category_italic');
            $table->dropColumn('sub_category_italic');
            $table->dropColumn('sub_sub_category_italic');
            $table->dropColumn('item_name_italic');
            $table->dropColumn('item_description_italic');
            $table->dropColumn('item_price_italic');

            $table->dropColumn('main_category_underlined');
            $table->dropColumn('sub_category_underlined');
            $table->dropColumn('sub_sub_category_underlined');
            $table->dropColumn('item_name_underlined');
            $table->dropColumn('item_description_underlined');
            $table->dropColumn('item_price_underlined');

        });
    }

}
