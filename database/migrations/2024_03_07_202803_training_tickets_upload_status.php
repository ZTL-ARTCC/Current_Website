<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('training_tickets', function (Blueprint $table) {
            $table->tinyInteger('vatusa_upload_status')->default(1);
        });

        Schema::table('training_tickets', function (Blueprint $table) {
            $table->tinyInteger('vatusa_upload_status')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('training_tickets', function ($table) {
            $table->dropColumn('vatusa_upload_status');
        });
    }
};
