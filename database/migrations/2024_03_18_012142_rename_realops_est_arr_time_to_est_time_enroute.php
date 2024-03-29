<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        DB::table("realops_flights")->truncate();
        Schema::table('realops_flights', function (Blueprint $table) {
            $table->renameColumn('est_arr_time', 'est_time_enroute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('realops_flights', function (Blueprint $table) {
            $table->renameColumn('est_time_enroute', 'est_arr_time');
        });
    }
};
