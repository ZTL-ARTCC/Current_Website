<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('solo_certs', function (Blueprint $table) {
            $table->tinyInteger('duration')->default(30);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('solo_certs', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
};
