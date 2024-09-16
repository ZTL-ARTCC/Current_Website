<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Feedback extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('trainer_feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trainer_id');
            $table->string('date_of_training')->nullable();
            $table->string('lesson_id')->nullable();
            $table->integer('service_level');
            $table->integer('student_cid')->nullable();
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
    public function down() {
        Schema::dropIfExists('trainer_feedback');
    }
}
