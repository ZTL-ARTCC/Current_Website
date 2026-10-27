<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('training_courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_id')->unique();
            $table->string('course_name');
            $table->boolean('certification')->default(true);
            $table->text('soi_link')->nullable()->default(null);
            $table->integer('associated_rating')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('training_courses');
    }
};
