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
        Schema::table('roster', function ($table) {
            $table->integer('atl_gnd')->default('0');
            $table->integer('atl_twr')->default('0');
            $table->integer('atl_app')->default('0');
            $table->integer('clt_gnd')->default('0');
            $table->integer('clt_twr')->default('0');
            $table->integer('clt_app')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('roster', function ($table) {
            $table->dropColumn('atl_gnd');
            $table->dropColumn('atl_twr');
            $table->dropColumn('atl_app');
            $table->dropColumn('clt_gnd');
            $table->dropColumn('clt_twr');
            $table->dropColumn('clt_app');
        });
    }
};
