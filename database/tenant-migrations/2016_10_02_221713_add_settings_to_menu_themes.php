<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingsToMenuThemes extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_themes', function ($table) {
            $table->boolean('has_background_image')->default(true);
            $table->boolean('has_background_color')->default(true);
            $table->boolean('has_use_category_images')->default(true);
            $table->boolean('has_parallax')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_themes', function ($table) {
            $table->dropColumn('has_background_image');
            $table->dropColumn('has_background_color');
            $table->dropColumn('has_use_category_images');
            $table->dropColumn('has_parallax');
        });
    }

}
