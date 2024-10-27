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
        Schema::create('pilot_passport_airfield_map', function (Blueprint $table) {
            $table->id();
            $table->string('airfield', length: 4);
            $table->integer('mapped_to');
        });

        Artisan::call('db:seed', [
            '--class' => 'PilotPassportAirfieldMapSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('pilot_passport_airfields_map');
    }
};
