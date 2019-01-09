<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ArtccOverflights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights_within_artcc', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('pilot_cid');
            $table->string('pilot_name');
            $table->string('callsign');
            $table->string('type');
            $table->string('dep');
            $table->string('arr');
            $table->text('route');
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
        Schema::dropIfExists('flights_within_artcc');
    }
}
