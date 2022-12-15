<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('training_tickets', function (Blueprint $table) {
            $table->date('start_date')->after('date')->nullable();
        });
        DB::statement("UPDATE training_tickets SET start_date = STR_TO_DATE(date, '%m/%d/%Y')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('training_tickets', function ($table) {
            if (Schema::hasColumn('start_date')) {
                $table->dropColumn('start_date');
            }
        });
    }
};
