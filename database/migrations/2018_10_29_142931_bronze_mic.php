<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BronzeMic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bronze_mic', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('controller_id');
            $table->integer('month');
            $table->integer('year');
            $table->string('month_hours');
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
        Schema::dropIfExists('bronze_mic');
    }
}
