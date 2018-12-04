<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Roster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roster', function(Blueprint $table) {
            $table->integer('id');
            $table->string('fname');
            $table->string('lname');
            $table->string('initials')->nullable();
            $table->string('email');
            $table->integer('rating_id');
            $table->integer('canTrain')->default(1);
            $table->integer('canEvents')->default(1);
            $table->integer('visitor');
            $table->string('visitor_from')->nullable();
            $table->integer('api_exempt')->default(0);
            $table->integer('status');
            $table->integer('loa')->default(0);
            $table->integer('del')->default(0);
            $table->integer('gnd')->default(0);
            $table->integer('twr')->default(0);
            $table->integer('app')->default(0);
            $table->integer('ctr')->default(0);
            $table->integer('train_pwr')->nullable();
            $table->integer('monitor_pwr')->nullable();
            $table->integer('opt')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('json_token', 2000)->nullable();
            $table->timestamps();
            $table->timestamp('added_to_facility')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roster');
    }
}
