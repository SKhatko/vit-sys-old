<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name');
            $table->integer('category_id')->unsigned()->index();
            $table->string('description')->nullable()->default(NULL);
            $table->string('image')->nullable()->default(NULL);
            $table->decimal('price', 5, 2)->nullable()->default(NULL);
            $table->integer('order_num')->default(99999);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('menu_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('menu_items');
    }

}
