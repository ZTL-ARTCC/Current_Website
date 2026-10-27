<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('training_lessons', function (Blueprint $table) {
            $table->id();
            $table->string('lesson_id')->unique();
            $table->string('lesson_name');
            $table->integer('course_id')->nullable()->default(null);
            $table->integer('instructor_qualification')->nullable()->default(null);
            $table->integer('sort_category_id')->nullable()->default(null);
            $table->boolean('proficiency_advance')->default(false);
            $table->boolean('solo')->default(false);
            $table->boolean('certification')->default(false);
            $table->boolean('ots')->default(false);
            $table->text('moodle_prerequisite')->nullable()->default(null);
            $table->text('soi_link')->nullable()->default(null);
            $table->boolean('active')->default(true);
            $table->integer('edited_by')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('training_lessons');
    }
};
