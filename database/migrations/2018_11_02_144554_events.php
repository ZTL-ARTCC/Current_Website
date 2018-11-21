<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Events extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('host')->nullable();
            $table->text('description');
            $table->string('date');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('banner_path')->nullable();
            $table->integer('status');
            $table->integer('reg');
            $table->timestamps();
        });

        Schema::create('event_registration', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id');
            $table->integer('controller_id');
            $table->integer('position_id');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->integer('status');
            $table->integer('choice_number');
            $table->integer('reminder')->nullable();
            $table->timestamps();
        });

        Schema::create('event_positions', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id');
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
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_registration');
        Schema::dropIfExists('event_positions');
    }
}
