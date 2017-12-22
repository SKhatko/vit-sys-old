<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdminCreateConfigTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->string('restaurant_name', 135)->default('Manager Console');
            $table->string('timezone', 75)->default('UTC');
            $table->time('day_start')->default('06:00:00');
            $table->time('day_end')->default('16:00:00');
            $table->time('night_end')->default('23:59:00');
            $table->string('currency', 3)->default('EUR');
            $table->integer('orange_num')->default(85);
            $table->integer('red_num')->default(100);
            $table->integer('max_reservations_per_hour')->default(30);
            $table->integer('max_online_persons')->default(8);

            $table->string('language', 10)->nullable()->default(NULL);

            $table->string('address', 135)->nullable()->default(NULL);
            $table->string('zip_code', 35)->nullable()->default(NULL);
            $table->string('city', 75)->nullable()->default(NULL);
            $table->string('phone', 75)->nullable()->default(NULL);
            $table->string('mobile', 75)->nullable()->default(NULL);
            $table->string('phone_2', 75)->nullable()->default(NULL);
            $table->string('email')->nullable()->default(NULL);
            $table->string('country', 5)->nullable()->default(NULL);
            $table->string('website', 135)->nullable()->default(NULL);
            $table->string('welcome_message', 255)->nullable()->default(NULL);

            $table->string('decimal_point', 10)->default('.');

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
