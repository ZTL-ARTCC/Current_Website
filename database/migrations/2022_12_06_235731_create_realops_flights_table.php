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
        Schema::create('realops_flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_pilot_id')->nullable()->references('id')->on('realops_pilots')->nullOnDelete();
            $table->string('flight_number');
            $table->date('flight_date');
            $table->time('dep_time');
            $table->string('dep_airport');
            $table->string('arr_airport');
            $table->time('est_arr_time')->nullable();
            $table->text('route')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('realops_flights');
    }
};
