<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryIdToMenuCategories extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_categories', function ($table) {
            $table->integer('parent_id')->unsigned()->index()->nullable()->default(NULL);

            $table->foreign('parent_id')->references('id')->on('menu_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_categories', function ($table) {
            $table->dropColumn('parent_id');
        });
    }

}
