<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePlanScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_plan_schedule', function (Blueprint $table) {
            $table->integer('default_table_plan_id')->nullable()->default(NULL);
            $table->integer('monday_day')->nullable()->default(NULL);
            $table->integer('monday_night')->nullable()->default(NULL);
            $table->integer('tuesday_day')->nullable()->default(NULL);
            $table->integer('tuesday_night')->nullable()->default(NULL);
            $table->integer('wednesday_day')->nullable()->default(NULL);
            $table->integer('wednesday_night')->nullable()->default(NULL);
            $table->integer('thursday_day')->nullable()->default(NULL);
            $table->integer('thursday_night')->nullable()->default(NULL);
            $table->integer('friday_day')->nullable()->default(NULL);
            $table->integer('friday_night')->nullable()->default(NULL);
            $table->integer('saturday_day')->nullable()->default(NULL);
            $table->integer('saturday_night')->nullable()->default(NULL);
            $table->integer('sunday_day')->nullable()->default(NULL);
            $table->integer('sunday_night')->nullable()->default(NULL);
        });

        DB::insert("INSERT INTO table_plan_schedule (`default_table_plan_id`) VALUES (DEFAULT )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('table_plan_schedule');
    }
}
