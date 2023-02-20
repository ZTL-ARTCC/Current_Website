<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class UpdateEventsAddTopicId extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('events', function ($table) {
            $table->integer('id_topic')->nullable();
            $table->string('banner_reduced_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('events', function ($table) {
            $table->dropColumn('id_topic');
            $table->dropColumn('banner_reduced_path');
        });
    }
}
