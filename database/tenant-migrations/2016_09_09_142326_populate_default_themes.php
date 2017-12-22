<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateDefaultThemes extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert("insert into menu_themes (`id`, `name`, `has_background`, `has_navigation_background_color`, `has_navigation_background_opacity`) VALUES (1, 'side_nav_scroll', true, true, true)");

        DB::insert("insert into menu_themes (`id`, `name`, `has_background`, `has_navigation_background_color`, `has_navigation_background_opacity`) VALUES (2, 'top_nav_tabs', true, false, false)");

        DB::insert("insert into menu_themes (`id`, `name`, `has_background`, `has_navigation_background_color`, `has_navigation_background_opacity`) VALUES (3, 'inner_adaptive_scroll_clean', false, false, false)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('menu_themes')->truncate();
    }

}
