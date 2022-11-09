<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('mentor_training_times', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mentor_id');
            $table->dateTime('slot');
            $table->integer('trainee_id')->nullable();
            $table->integer('position_id')->nullable();
            $table->text('trainee_comments')->nullable();
            $table->text('cancel_message')->nullable();
            $table->integer('rat')->nullable();
            $table->integer('mentor_powr')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('mentor_training_times');
    }
};
