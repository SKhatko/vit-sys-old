<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateDataToCourseMenuItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $menuGroups = DB::table('menu_groups')->get();

        foreach ($menuGroups as $menuGroup) {

            $items = DB::table('menu_group_item')
                ->where('group_id', '=', $menuGroup->id)
                ->leftJoin('menu_items', 'menu_group_item.item_id', '=', 'menu_items.id')
                ->get();

            $lastCategoryId = NULL;
            $lastCourseId = NULL;
            foreach ($items as $item) {
                if ($item->category_id != $lastCategoryId) {
                    $lastCourseId = DB::table('courses')->insertGetId([
                        'group_id' => $menuGroup->id
                    ]);
                }

                $lastCategoryId = $item->category_id;

                //$lastCourse->menu_items()->attach($item->id);
                DB::table('course_menu_item')->insert([
                    'course_id' => $lastCourseId,
                    'item_id' => $item->id
                ]);
            }
        }

        Schema::disableForeignKeyConstraints();

        Schema::drop('menu_group_item');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::create('menu_group_item', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('group_id')->unsigned()->index();
            $table->integer('item_id')->unsigned()->index();

            $table->foreign('group_id')->references('id')->on('menu_groups')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('menu_items')->onDelete('cascade');
        });

        $menuGroups = DB::table('menu_groups')->distinct()->get();

        foreach ($menuGroups as $menuGroup) {

            $courses = DB::table('courses')
                ->where('group_id', '=', $menuGroup->id)->pluck('id')->toArray();

            $items = DB::table('course_menu_item')
                ->whereIn('course_menu_item.course_id', $courses)
                ->leftJoin('menu_items', 'course_menu_item.item_id', '=', 'menu_items.id')
                ->get();

            foreach ($items as $item) {
                DB::table('group_menu_item')->insert([
                    'group_id' => $menuGroup->id,
                    'item_id' => $item->id
                ]);
            }
        }

        Schema::disableForeignKeyConstraints();

        Schema::drop('course_menu_item');

    }
}
