<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasStickyNavToThemes extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_themes', function ($table) {
            $table->boolean('has_sticky_nav')->default(false);
        });

        DB::table('menu_themes')
            ->where('name', '=', 'image_separators_scroll')
            ->update([
                'has_sticky_nav' => true
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
            $table->dropColumn('has_sticky_nav');
        });
    }

}
