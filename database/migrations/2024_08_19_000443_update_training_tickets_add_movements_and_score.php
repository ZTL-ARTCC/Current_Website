<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('training_tickets', function ($table) {
            $table->tinyInteger('score');
            $table->integer('movements')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('training_tickets', function ($table) {
            $table->dropColumn('score');
            $table->dropColumn('movements');
        });
    }
};
