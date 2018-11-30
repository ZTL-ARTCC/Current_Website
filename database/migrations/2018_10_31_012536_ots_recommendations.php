<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OtsRecommendations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ots_recommendations', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('controller_id');
            $table->integer('recommender_id');
            $table->integer('position');
            $table->integer('ins_id')->nullable();
            $table->integer('status');
            $table->string('report')->nullable();
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
        Schema::dropIfExists('ots_recommendations');
    }
}
