<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveSideNavScrollTheme extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('menu_config')
            ->where('theme_id', '=', 1)
            ->update([
                'theme_id' => 2
            ]);

        DB::table('menu_themes')
            ->where('name', '=', 'side_nav_scroll')
            ->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //no reverse
    }

}
