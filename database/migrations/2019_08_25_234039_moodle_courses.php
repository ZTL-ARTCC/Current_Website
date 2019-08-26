<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoodleCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moodle_course_assignments', function(Blueprint $table) {
            $table->increments('id');
            $table->string('course_name');
            $table->integer('course_id');
            $table->integer('rating_id');
            $table->integer('isVisitor');
            $table->integer('mdl_enrol_id');
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
        Schema::dropIfExists('moodle_course_assignments');
    }
}
