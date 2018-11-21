<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Atc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_atc', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('cid');
            $table->string('name');
            $table->string('position');
            $table->string('freq');
            $table->string('time_logon');
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
        Schema::dropIfExists('online_atc');
    }
}
