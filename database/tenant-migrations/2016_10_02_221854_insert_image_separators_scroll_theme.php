<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertImageSeparatorsScrollTheme extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('menu_themes')->insert([
            'name' => 'image_separators_scroll',
            'has_background' => true,
            'has_background_image' => false,
            'has_background_color' => false,
            'has_use_category_images' => true,
            'has_navigation_background_color' => true,
            'has_navigation_background_opacity' => true,
            'has_scroll_top' => true,
            'has_parallax' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('menu_themes')
            ->where('name', '=', 'image_separators_scroll')
            ->delete();
    }

}
