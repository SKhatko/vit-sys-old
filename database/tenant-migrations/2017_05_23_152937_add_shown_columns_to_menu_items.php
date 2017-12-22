<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShownColumnsToMenuItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function ($table) {
            $table->boolean('online_shown')->default(true);
            $table->boolean('preorders_shown')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_items', function ($table) {
            $table->dropColumn(['online_shown', 'preorders_shown']);
        });
    }
}
