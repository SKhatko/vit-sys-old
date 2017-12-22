<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewsletterColumnsToClients extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function ($table) {
            $table->boolean('restaurant_newsletter')->default(true);
            $table->boolean('vitisch_newsletter')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function ($table) {
            $table->dropColumn('restaurant_newsletter');
            $table->dropColumn('vitisch_newsletter');
        });
    }

}
