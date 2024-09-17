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
        Schema::create('live_event', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_title');
            $table->text('body_public');
            $table->text('body_private');
            $table->integer('staff_member');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('live_event');
    }
};
