<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('roster', function (Blueprint $table) {
            $table->boolean('onboarding_complete')->default(false);
        });

        DB::statement("UPDATE roster SET onboarding_complete = true");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('roster', function (Blueprint $table) {
            $table->dropColumn('onboarding_complete');
        });
    }
};
