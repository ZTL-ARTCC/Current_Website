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
        Schema::create('pilot_passport_log', function (Blueprint $table) {
            $table->id();
            $table->integer('cid');
            $table->string('airfield', length: 4);
            $table->dateTime('visited_on');
            $table->string('callsign');
            $table->string('aircraft_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('pilot_passport_log');
    }
};
