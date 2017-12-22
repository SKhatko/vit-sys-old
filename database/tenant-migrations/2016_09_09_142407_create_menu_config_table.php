<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuConfigTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_config', function (Blueprint $table) {
            $defaultFont = "Open Sans";

            $table->engine = 'InnoDB';

            //theme
            $table->integer('theme_id')->unsigned()->nullable()->default(1);

            //general
            $table->boolean('display_images')->default(true);
            $table->boolean('display_prices')->default(true);

            //background
            $table->string('background_color', 35)->nullable()->default('#000000');
            $table->string('background_image', 255)->nullable()->default(NULL);
            $table->string('background_type', 35)->nullable()->default('color');
            //color, image, inherit (inherit means take categories' photos)

            //content section
            $table->string('content_font')->nullable()->default($defaultFont);
            $table->integer('content_font_size')->nullable()->default(16);
            $table->string('content_font_color', 35)->nullable()->default('#000000');
            $table->string('content_background_color', 35)->nullable()->default('#ffffff');
            $table->integer('content_background_opacity')->nullable()->default('92');

            //navigation
            $table->string('navigation_font')->nullable()->default($defaultFont);
            $table->integer('navigation_font_size')->nullable()->default(18);
            $table->string('navigation_font_color', 35)->nullable()->default('#980000');
            $table->string('navigation_background_color', 35)->nullable()->default('#ffffff');
            $table->integer('navigation_background_opacity')->nullable()->default('92');


            //main category title
            $table->string('main_category_font')->nullable()->default($defaultFont);
            $table->integer('main_category_font_size')->nullable()->default(22);
            $table->string('main_category_font_color', 35)->nullable()->default('#000000');


            //sub category title
            $table->string('sub_category_font')->nullable()->default($defaultFont);
            $table->integer('sub_category_font_size')->nullable()->default(19);
            $table->string('sub_category_font_color', 35)->nullable()->default('#980000');

            //sub sub category title
            $table->string('sub_sub_category_font_color', 35)->nullable()->default('#666666');

            //css
            $table->string('custom_css', 1000)->nullable()->default(NULL);

            $table->foreign('theme_id')->references('id')->on('menu_themes');
        });

        //insert the table's only row with default NULL values
        DB::insert("insert into menu_config (`theme_id`) VALUES 
			(1)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('menu_config');
    }

}
