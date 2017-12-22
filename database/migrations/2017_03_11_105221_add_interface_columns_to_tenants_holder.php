<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInterfaceColumnsToTenantsHolder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenants', function ($table) {
            $table->boolean('reception_enabled')->default(true);
            $table->boolean('restaurant_enabled')->default(true);
            $table->boolean('clients_enabled')->default(true);
            $table->boolean('analytics_enabled')->default(true);
            $table->boolean('admin_enabled')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenants', function ($table) {
            $table->dropColumn('reception_enabled');
            $table->dropColumn('restaurant_enabled');
            $table->dropColumn('clients_enabled');
            $table->dropColumn('analytics_enabled');
            $table->dropColumn('admin_enabled');
        });
    }
}
