<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminConfigColumns extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('config', function ($table) {
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('config', function ($table) {
            $table->dropColumn('address');
            $table->dropColumn('zip_code');
            $table->dropColumn('city');
            $table->dropColumn('phone');
            $table->dropColumn('mobile');
            $table->dropColumn('phone_2');
            $table->dropColumn('email');
            $table->dropColumn('country');
            $table->dropColumn('website');
            $table->dropColumn('welcome_message');
        });
    }

}
