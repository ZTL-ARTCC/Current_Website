<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DiscordInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discord_users', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('cid');
            $table->string('name');
            $table->integer('rating_id');
            $table->bigInteger('discord_id');
            $table->string('discord_username');
            $table->string('online_time_month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discord_users');
    }
}
