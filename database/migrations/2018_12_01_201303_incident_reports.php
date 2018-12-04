<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncidentReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_reports', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('controller_id')->nullable();
            $table->string('controller_callsign');
            $table->integer('reporter_id')->nullable();
            $table->string('reporter_callsign');
            $table->string('aircraft_callsign')->nullable();
            $table->string('time');
            $table->string('date');
            $table->text('description');
            $table->integer('status');
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
        Schema::dropIfExists('incident_reports');
    }
}
