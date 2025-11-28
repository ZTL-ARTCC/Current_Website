<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('prd_route', function (Blueprint $table) {
            $table->id();
            $table->string('orig', length: 5);
            $table->text('route_string');
            $table->string('dest', length: 5);
            $table->string('hours1', length: 50);
            $table->string('hours2', length: 50);
            $table->string('hours3', length: 50);
            $table->string('type', length: 4);
            $table->string('area', length: 100);
            $table->integer('altitude')->nullable();
            $table->string('aircraft', length: 255);
            $table->string('direction', length: 255);
            $table->integer('seq');
            $table->string('dcntr', length: 4);
            $table->string('acntr', length: 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('prd_route');
    }
};
