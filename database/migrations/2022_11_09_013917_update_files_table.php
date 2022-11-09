<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('files', function ($table) {
            $table->integer('disp_order')->nullable();
            $table->string('permalink')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('files', function ($table) {
            $table->dropColumn('disp_order');
            $table->dropColumn('permalink');
        });
    }
};
