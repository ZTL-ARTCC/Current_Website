<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Roster extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('setmore', function (Blueprint $table) {
            $table->increments('id');
            $table->string('setmore_key', 10);
            $table->datetime('start_time');
            $table->integer('duration')->nullable();
            $table->string('staff_key', 9);
            $table->string('staff_name', 100)->nullable();
            $table->string('service_key', 9);
            $table->string('service_description', 100)->nullable();
            $table->integer('customer_cid')->nullable();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('setmore');
    }
}
