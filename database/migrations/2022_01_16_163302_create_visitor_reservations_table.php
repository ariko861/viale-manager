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
        Schema::dropIfExists('visitor_reservation');

        Schema::create('visitor_reservation', function (Blueprint $table) {
            //
            $table->id();
            $table->foreignId('visitor_id')->constrained()->onDelete('cascade');
            $table->integer('reservation_id')->constrained()->onDelete('cascade');
            $table->boolean('contact');
            $table->float('price', 8, 2)->nullable();
            $table->foreignId('room_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visitor_reservation', function (Blueprint $table) {
            //
        });
    }
}
