<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoppedDaysTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stopped_days', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->date('date');
            $table->string('shift', 10); //'day' or 'night'
            $table->boolean('online_closed')->default(false);
            $table->boolean('system_closed')->default(false);
            $table->integer('online_stop_num')->nullable()->default(NULL);
            $table->integer('system_stop_num')->nullable()->default(NULL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stopped_days');
    }

}
