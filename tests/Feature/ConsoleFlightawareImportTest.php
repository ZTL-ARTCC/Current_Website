<?php

namespace Tests\Feature;

use Config;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ConsoleFlightawareImportTest extends TestCase {
    #[Test]
    public function it_has_command(): void {
        $this->assertTrue(class_exists(\App\Console\Commands\FlightawareImport::class));
    }

    #[Test]
    public function it_runs_successfully(): void {
        if (!Config::get('flightaware.dryrun') || (Config::get('flightaware.max_flights') != 1)) { // Do not run... this will cost money!
            $this->fail('Unable to test FlightawareImport -- must be configured with FLIGHTAWARE_MAX_FLIGHTS=1 and FLIGHTAWARE_DRYRUN=true');
        } else {
            $this->artisan('Realops:LoadFromFlightaware')->assertSuccessful();
        }
    }
}
