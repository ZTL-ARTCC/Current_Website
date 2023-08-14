<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('roster', function ($table) {
            $table->string('timezone', '128')->default("America/New_York"); // Default to EST/EDT
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('roster', function ($table) {
            $table->dropColumn('timezone');
        });
    }
};
