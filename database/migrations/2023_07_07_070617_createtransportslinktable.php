<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createtransportslinktable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('transports_links', function (Blueprint $table) {
            $table->id();
            $table->uuid('link_token')->unique();
            $table->date('date');
            $table->integer('interval');
            $table->enum('type', ['offer_places', 'lookfor_places']);
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
        //
    }
}
