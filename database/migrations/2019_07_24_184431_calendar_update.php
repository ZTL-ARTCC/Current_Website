<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CalendarUpdate extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('calendar', function ($table) {
            // Visible = 1, Not Visible = 0.
            $table->integer('visible')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('calendar', function ($table) {
            $table->dropColumn('visible');
        });
    }
}
