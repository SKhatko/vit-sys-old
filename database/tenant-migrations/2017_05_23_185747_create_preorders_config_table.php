<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreordersConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorders_config', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->boolean('display_images')->default(true);
            $table->boolean('display_prices')->default(true);
            $table->integer('hours_limit')->unsigned()->default(48);
        });

        DB::insert("insert into preorders_config (`hours_limit`) VALUES (48)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('preorders_config');
    }
}
