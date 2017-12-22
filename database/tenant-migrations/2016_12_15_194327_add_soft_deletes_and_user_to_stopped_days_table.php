<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesAndUserToStoppedDaysTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stopped_days', function ($table) {
            $table->string('user', 75)->nullable()->default(NULL);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stopped_days', function ($table) {
            $table->dropColumn('user');
            $table->dropColumn('deleted_at');
        });
    }
}
