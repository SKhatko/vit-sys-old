<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupPreorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_preorder', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('preorder_id')->unsigned()->index();
            $table->integer('group_id')->unsigned()->index();
            $table->string('items');
            $table->integer('quantity')->unsigned();

            $table->foreign('preorder_id')->references('id')->on('preorders');
            $table->foreign('group_id')->references('id')->on('menu_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('group_preorder');
    }
}
