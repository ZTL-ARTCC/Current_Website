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
            $table->string('twr_solo_fields')->nullable();
            $table->string('twr_solo_expires')->nullable();
            $table->integer('max')->default(0)->nullable();
            $table->integer('max_minor_app')->nullable();
            $table->integer('max_minor_twr')->nullable();
            $table->integer('max_minor_gnd')->nullable();
            $table->integer('max_minor_del')->nullable();
            $table->integer('max_major_app')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('roster', function ($table) {
            $table->dropColumn('twr_solo_fields');
            $table->dropColumn('twr_solo_expires');
            $table->dropColumn('max');
            $table->dropColumn('max_minor_app');
            $table->dropColumn('max_minor_twr');
            $table->dropColumn('max_minor_gnd');
            $table->dropColumn('max_minor_del');
            $table->dropColumn('max_major_app');
        });
    }
};
