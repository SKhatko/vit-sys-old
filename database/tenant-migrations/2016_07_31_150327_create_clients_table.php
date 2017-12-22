<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('first_name', 135);
            $table->string('last_name', 135)->nullable()->default(NULL);
            $table->string('phone', 75)->nullable()->default(NULL);
            $table->string('mobile', 75)->nullable()->default(NULL);
            $table->string('email')->nullable()->default(NULL);
            $table->integer('status_id')->unsigned()->index()->default(1);
            $table->string('sticky_note')->nullable()->default(NULL);
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('client_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('clients');
    }

}
