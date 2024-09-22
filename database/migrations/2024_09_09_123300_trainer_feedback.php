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
        Schema::create('trainer_feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trainer_id');
            $table->string('feedback_date')->nullable();
            $table->integer('service_level');
            $table->string('position_trained')->nullable();
            $table->integer('booking_method');
            $table->integer('training_method');
            $table->text('comments');
            $table->string('student_name')->nullable();
            $table->string('student_email')->nullable();
            $table->integer('student_cid')->nullable();
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
};
