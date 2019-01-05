<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PyriteMic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('pyrite_mic', function(Blueprint $table) {
             $table->increments('id');
             $table->integer('controller_id');
             $table->integer('year');
             $table->string('year_hours');
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
         Schema::dropIfExists('pyrite_mic');
     }
}
