<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('aircraft', function (Blueprint $table) {
            $table->id();
            $table->string('ac_type')->unique();
            $table->enum('icao_wtc', ['L', 'M', 'H', 'J'])->nullable();
            $table->string('equipment')->nullable();
            $table->string('transponder')->nullable();
            $table->enum('perf_cat', ['A', 'B', 'C', 'D', 'E', 'H'])->nullable();
            $table->string('pbn')->nullable();
        });

        Artisan::call('db:seed', [
            '--class' => 'AircraftSeeder',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('aircraft');
    }
};
