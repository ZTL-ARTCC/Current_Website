<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('atc_bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('vatsim_id')->unique()->nullable();
            $table->string('callsign');
            $table->integer('cid');
            $table->string('type');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->timestamps();

            $table->foreign('cid')->references('id')->on('roster')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('atc_bookings');
    }
};
