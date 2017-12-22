<?php

use Illuminate\Database\Migrations\Migration;

class ChangeObjectNumTypeInSectionMapObjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section_map_objects', function ($table) {
            $table->string('object_num')->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section_map_objects', function ($table) {
            $table->integer('object_num')->change();
        });
    }
}
