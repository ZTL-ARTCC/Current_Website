<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('training_tickets', function (Blueprint $table) {
            $table->integer('position')->nullable()->change();
            $table->integer('session_id')->nullable()->change();
            $table->integer('type')->nullable()->change();
            $table->string('date')->nullable()->change();
            $table->string('start_time')->nullable()->change();
            $table->string('end_time')->nullable()->change();
            $table->string('duration')->nullable()->change();
            $table->boolean('cert')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('training_tickets', function (Blueprint $table) {
            $table->integer('position')->nullable(false)->change();
            $table->integer('session_id')->nullable(false)->change();
            $table->integer('type')->nullable(false)->change();
            $table->string('date')->nullable(false)->change();
            $table->string('start_time')->nullable(false)->change();
            $table->string('end_time')->nullable(false)->change();
            $table->string('duration')->nullable(false)->change();
            $table->integer('cert')->change();
        });
    }
};
