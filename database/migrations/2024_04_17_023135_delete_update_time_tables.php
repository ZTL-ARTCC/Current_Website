<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::dropIfExists('controller_log_update');
        Schema::dropIfExists('flights_within_artcc_updates');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        //
    }
};
