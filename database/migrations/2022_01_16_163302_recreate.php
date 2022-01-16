<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Recreate extends Migration
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
