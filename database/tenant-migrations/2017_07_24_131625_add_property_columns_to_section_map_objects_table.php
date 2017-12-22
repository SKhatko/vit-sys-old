<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertyColumnsToSectionMapObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        DB::table('section_map_objects')->truncate();
        
        Schema::table('section_map_objects', function ($table) {
            $table->integer('persons_num')->nullable()->default(NULL)->after('height');
            $table->integer('table_plan_id')->after('section_id');
            $table->integer('object_num')->nullable()->default(NULL)->after('height');
            $table->string('label', 255)->nullable()->default(NULL)->after('height');
            $table->integer('border_radius')->default(0)->after('height');
        });

//        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section_map_objects', function ($table) {
            $table->dropColumn(['persons_num', 'table_plan_id', 'object_num', 'label', 'border_radius']);
        });
    }
}
