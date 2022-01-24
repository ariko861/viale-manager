<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_reservation', function (Blueprint $table) {
            $table->integer('visitor_id')->unsigned();
            $table->integer('reservation_id')->unsigned();
            $table->foreign('visitor_id')->references('id')->on('visitors');
            $table->foreign('reservation_id')->references('id')->on('reservations');
            $table->boolean('contact');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitor_reservations');
    }
}
