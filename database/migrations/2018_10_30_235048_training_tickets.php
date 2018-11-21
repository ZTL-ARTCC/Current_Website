<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrainingTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_tickets', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('controller_id');
            $table->integer('trainer_id');
            $table->integer('position');
            $table->integer('type');
            $table->string('date');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('duration');
            $table->text('comments')->nullable();
            $table->text('ins_comments')->nullable();
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
        Schema::dropIfExists('training_tickets');
    }
}
