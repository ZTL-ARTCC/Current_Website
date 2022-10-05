<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Scenery extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('scenery', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('airport');
            $table->string('developer');
            $table->integer('sim');
            $table->string('price');
            $table->string('currency');
            $table->string('link');
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('scenery');
    }
}
