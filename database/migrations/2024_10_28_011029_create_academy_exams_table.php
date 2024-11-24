<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('academy_exams', function (Blueprint $table) {
            $table->id();
            $table->integer('controller_id');
            $table->string('name');
            $table->string('date')->nullable();
            $table->tinyInteger('success')->default(3);
            $table->tinyInteger('grade')->nullable();
            $table->timestamps();

            $table->foreign('controller_id')->references('id')->on('roster')->cascadeOnDelete();
            $table->unique(['controller_id', 'name']);
            $table->index('controller_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('academy_exams');
    }
};
