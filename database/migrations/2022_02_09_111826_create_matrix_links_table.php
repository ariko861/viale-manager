<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatrixLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matrix_links', function (Blueprint $table) {
            $table->id();
            $table->string('link')->unique();
            $table->string('roomID')->nullable();
            $table->string('homeserver')->default("https://matrix.org");
            $table->string('filteredUser')->default("");
            $table->boolean('gallery')->default(false);
            $table->boolean('displayDate')->default(false);
            $table->boolean('displayAddress')->default(false);

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
        Schema::dropIfExists('matrix_links');
    }
}
