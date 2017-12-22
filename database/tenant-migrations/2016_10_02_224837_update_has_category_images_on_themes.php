<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHasCategoryImagesOnThemes extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('menu_themes')
            ->where('name', '=', 'side_nav_scroll')
            ->orWhere('name', '=', 'inner_adaptive_scroll_clean')
            ->update([
                'has_use_category_images' => false
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //for no apparent reason but I still do it..
        DB::table('menu_themes')
            ->where('name', '=', 'side_nav_scroll')
            ->orWhere('name', '=', 'inner_adaptive_scroll_clean')
            ->update([
                'has_use_category_images' => true
            ]);
    }

}
