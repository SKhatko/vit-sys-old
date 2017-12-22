<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasScrollTopToMenuThemes extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_themes', function ($table) {
            $table->boolean('has_scroll_top')->default(true);
        });

        DB::table('menu_themes')
            ->where('name', '=', 'side_nav_scroll')
            ->orWhere('name', '=', 'inner_adaptive_scroll_clean')
            ->update([
                'has_scroll_top' => false
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
            $table->dropColumn('has_scroll_top');
        });
    }

}
