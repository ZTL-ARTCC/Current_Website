<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MetarUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airport_weather', function(Blueprint $table) {
            $table->increments('id');
            $table->string('icao');
            $table->text('metar')->nullable();
            $table->text('taf')->nullable();
            $table->string('visual_conditions')->nullable();
            $table->string('altimeter')->nullable();
            $table->string('wind')->nullable();
            $table->string('temp')->nullable();
            $table->string('dp')->nullable();
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
        Schema::dropIfExists('airport_weather');
    }
}
