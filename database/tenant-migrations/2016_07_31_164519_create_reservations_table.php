<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name')->nullable()->default(NULL);
            $table->integer('persons_num');
            $table->datetime('time');
            $table->string('description')->nullable()->default(NULL);

            $table->integer('client_id')->unsigned()->index()->nullable()->default(NULL);
            $table->integer('company_id')->unsigned()->index()->nullable()->default(NULL);
            $table->integer('status_id')->unsigned()->index()->default(1);
            $table->integer('table_id')->unsigned()->index()->nullable()->default(NULL);
            $table->integer('event_type_id')->unsigned()->index()->default(1);
            $table->integer('offer_status_id')->unsigned()->index()->default(1);

            $table->string('offer_file')->nullable()->default(NULL);

            $table->boolean('is_walkin')->default(false);

            $table->boolean('showed')->default(false);
            $table->boolean('left')->default(false);
            $table->datetime('showed_at')->nullable()->default(NULL);
            $table->datetime('left_at')->nullable()->default(NULL);

            $table->string('client_token', 135)->nullable()->default(NULL);

            $table->string('created_by', 75)->nullable()->default(NULL);
            $table->string('updated_by', 75)->nullable()->default(NULL);

            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('status_id')->references('id')->on('reservation_statuses');

            //$table->foreign('table_id')->references('id')->on('tables')->onDelete('set null');
            //we don't want the constraints on table ID

            $table->foreign('event_type_id')->references('id')->on('event_types');
            $table->foreign('offer_status_id')->references('id')->on('offer_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reservations');
    }

}
