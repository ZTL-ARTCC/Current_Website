<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('audits', function($table) {
            $table->integer('impersonated_by_id')->nullable();

            $table->foreign('impersonated_by_id')->references('id')->on('roster')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audits', function($table) {
            $table->dropColumn('impersonated_by_id');
        });
    }
};
