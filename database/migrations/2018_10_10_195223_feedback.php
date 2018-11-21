<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Feedback extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('controller_id');
            $table->string('position');
            $table->integer('service_level');
            $table->string('callsign');
            $table->string('pilot_name')->nullable();
            $table->string('pilot_email')->nullable();
            $table->integer('pilot_cid')->nullable();
            $table->text('comments')->nullable();
            $table->text('staff_comments')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('feedback');
    }
}
