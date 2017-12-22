<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigScheduleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_schedule', function (Blueprint $table) {
            $table->boolean('monday')->default(false);
            $table->boolean('tuesday')->default(false);
            $table->boolean('wednesday')->default(false);
            $table->boolean('thursday')->default(false);
            $table->boolean('friday')->default(false);
            $table->boolean('saturday')->default(false);
            $table->boolean('sunday')->default(false);

            $table->string('monday_times', 1000)->nullable()->default(NULL);
            $table->string('tuesday_times', 1000)->nullable()->default(NULL);
            $table->string('wednesday_times', 1000)->nullable()->default(NULL);
            $table->string('thursday_times', 1000)->nullable()->default(NULL);
            $table->string('friday_times', 1000)->nullable()->default(NULL);
            $table->string('saturday_times', 1000)->nullable()->default(NULL);
            $table->string('sunday_times', 1000)->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('config_schedule');
    }

}
