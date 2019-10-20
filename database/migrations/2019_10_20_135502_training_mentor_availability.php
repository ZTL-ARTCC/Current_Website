<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class training_mentor_availability extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_mentor_availability', function(Blueprint $table) {
            $table->integer('id');
            $table->integer('mentor_id');
            $table->datetime('slot');
            $table->interger('trainee_id')->nullabe();
            $table->interger('position_id')->nullabe();
            $table->string('trainee_comments')->nullabe();
            $table->string('cancel_message')->nullabe();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->interger('rat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_mentor_availability');
    }
}