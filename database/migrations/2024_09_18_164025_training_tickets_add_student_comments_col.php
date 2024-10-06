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
            $table->text('student_comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('training_tickets', function (Blueprint $table) {
            $table->dropColumn('student_comments');
        });
    }
};
