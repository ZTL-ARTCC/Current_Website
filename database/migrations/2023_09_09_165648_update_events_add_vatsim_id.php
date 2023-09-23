<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('events', function ($table) {
            $table->integer('vatsim_id')->nullable(); // Default to a normal event
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('events', function ($table) {
            $table->dropColumn('vatsim_id');
        });
    }
};
