<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Calendar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('date');
            $table->string('time')->nullable();
            $table->text('body');
            // Set to 1 for visible, set to 0 for not visible.
            $table->integer('visible')->default(1);
            $table->integer('type');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('calendar');
    }
}
