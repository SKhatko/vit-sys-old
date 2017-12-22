<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffdaysTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offdays', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->date('date');
            $table->boolean('enabled');
            $table->string('times', 1000)->nullable()->default(NULL);
            $table->string('reason_for_change')->nullable()->default(NULL);
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
        Schema::drop('offdays');
    }

}
