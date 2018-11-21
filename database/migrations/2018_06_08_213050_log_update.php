<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LogUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controller_log_update', function(Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->timestamps();
		});
		
		Schema::create('controller_log', function(Blueprint $table){
			$table->increments('id');
			$table->integer('cid');
			$table->string('name');
			$table->string('position');
			$table->string('duration');
			$table->string('date');
			$table->string('time_logon');
			$table->string('streamupdate');
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
        Schema::dropIfExists('controller_log_update');
		Schema::dropIfExists('controller_log');
    }
}
