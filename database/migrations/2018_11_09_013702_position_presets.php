<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PositionPresets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position_presets', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('first_position');
            $table->string('last_position');
            $table->timestamps();
        });

        Schema::create('preset_positions', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
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
        Schema::dropIfExists('position_presets');
        Schema::dropIfExists('preset_positions');
    }
}
