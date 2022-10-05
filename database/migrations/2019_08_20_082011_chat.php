<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Chat extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('chat_room', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cid');
            $table->string('c_name');
            $table->text('message');
            $table->integer('deleted');
            $table->string('format_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('chat_room');
    }
}
