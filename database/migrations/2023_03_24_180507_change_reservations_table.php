<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('reservations', function (Blueprint $table) {
            //
            $table->boolean("hasCarPlaces")->default(false);
            $table->boolean("lookForCarPlaces")->default(false);
            $table->boolean("shareEmail")->default(false);
            $table->boolean("sharePhone")->default(false);
            $table->integer("numberCarPlaces")->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
