<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasMenusBackgroundToMenuThemes extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_themes', function ($table) {
            $table->boolean('has_menus_background')->default(true);
        });

        DB::table('menu_themes')
            ->where('name', '=', 'inner_adaptive_scroll_clean')
            ->update([
                'has_menus_background' => false
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_themes', function ($table) {
            $table->dropColumn('has_menus_background');
        });
    }

}
