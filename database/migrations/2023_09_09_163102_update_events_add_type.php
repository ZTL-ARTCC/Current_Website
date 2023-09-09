<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('events', function ($table) {
            // Event Types:
            // 0 - Our Event
            // 1 - Support Event
            // 2 - Support Event (Autocreated & Unapproved)
            $table->integer('type')->default(0); // Default to a normal event
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('events', function ($table) {
            $table->dropColumn('type');
        });
    }
};
