<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShownColumnsAndDeleteQuantitiesDataOnMenuGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_groups', function ($table) {
            $table->boolean('online_shown')->default(true);
            $table->boolean('preorders_shown')->default(true);
            $table->dropColumn('quantities_data'); //data will be lost
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_groups', function ($table) {
            $table->dropColumn(['online_shown', 'preorders_shown']);
            $table->binary('quantities_data')->nullable()->default(NULL);
        });
    }
}
