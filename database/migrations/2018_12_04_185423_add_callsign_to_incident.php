<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCallsignToIncident extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incident_reports', function(Blueprint $table) {
            $table->string('controller_callsign')->after('controller_id');
            $table->string('reporter_callsign')->after('reporter_id');
            $table->string('aircraft_callsign')->after('reporter_callsign')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
