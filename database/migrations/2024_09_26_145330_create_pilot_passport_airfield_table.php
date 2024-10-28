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
        Schema::create('pilot_passport_airfield', function (Blueprint $table) {
            $table->string('id', length: 4)->primary();
            $table->double('latitude');
            $table->double('longitude');
            $table->integer('elevation');
            $table->string('name', length: 255)->nullable();
            $table->text('description')->nullable();
        });

        Artisan::call('db:seed', [
            '--class' => 'PilotPassportAirfieldSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('pilot_passport_airfield');
    }
};
