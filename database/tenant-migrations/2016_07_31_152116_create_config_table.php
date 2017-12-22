<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->string('restaurant_name', 135)->default('Restaurant Name');
            $table->string('timezone', 75)->default('UTC');
            $table->time('day_start')->default('06:00:00');
            $table->time('day_end')->default('16:00:00');
            $table->time('night_end')->default('23:59:00');
            $table->string('currency', 3)->default('EUR');
            $table->integer('orange_num')->default(85);
            $table->integer('red_num')->default(100);
            $table->integer('max_reservations_per_hour')->default(30);
            $table->integer('max_online_persons')->default(8);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('config');
    }

}
