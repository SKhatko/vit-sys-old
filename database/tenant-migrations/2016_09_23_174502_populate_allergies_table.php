<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateAllergiesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (1, 'gluten', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (2, 'crustaceans', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (3, 'eggs', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (4, 'fish', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (5, 'peanuts', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (6, 'soybeans', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (7, 'milk', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (8, 'nuts', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (9, 'celeriac', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (10, 'mustard', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (11, 'sesame', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (12, 'sulfur_dioxide', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (13, 'lupines', 'food')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (14, 'molluscs', 'food')");

        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (15, 'articial_dyes', 'drinks')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (16, 'preservative', 'drinks')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (17, 'antioxidant', 'drinks')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (18, 'flavor_enhancer', 'drinks')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (19, 'acidifier', 'drinks')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (20, 'sulfur_dioxide', 'drinks')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (21, 'sweetener', 'drinks')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (22, 'stabilizer', 'drinks')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (23, 'caffeine', 'drinks')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (24, 'quinene', 'drinks')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (25, 'taurine', 'drinks')");
        DB::insert("insert into allergies (`id`, `name`, `category`) VALUES (26, 'tannins', 'drinks')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('allergies')->truncate();
    }

}
