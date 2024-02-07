<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('roster', function ($table) {
            $table->integer('name_privacy')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('roster', function ($table) {
            $table->dropColumn('name_privacy');
        });
    }
};
