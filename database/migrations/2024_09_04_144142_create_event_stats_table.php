<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('event_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('event_id');
            $table->json('controllers_by_rating');
            $table->json('movements');
            $table->json('peak_aar')->nullable();
            $table->json('peak_adr')->nullable();
            $table->json('peak_delays')->nullable();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('event_stats');
    }
};
