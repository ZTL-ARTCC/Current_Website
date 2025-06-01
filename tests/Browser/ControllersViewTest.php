<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Authentication;
use Tests\DuskTestCase;

class ControllersViewTest extends DuskTestCase {

    public function test_controller_dashboard(): void {
        $this->browse(function (Browser $browser) {
            Authentication::login($browser);
            $browser->visit('/dashboard')
                    ->assertSee('Controller Dashboard');
        });
    }

    public function test_controller_profile(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/controllers/profile')
                    ->assertSee('My Profile');
        });
    }

    public function test_controller_roster(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/controllers/roster')
                    ->assertSee('Roster');
        });
    }

    public function test_controller_events(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/controllers/events')
                    ->assertSee('Events');
        });
    }

    public function test_controller_bookings(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/controllers/bookings')
                    ->assertSee('ATC Bookings');
        });
    }

    public function test_controller_files(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/controllers/files')
                    ->assertSee('Files');
        });
    }

    public function test_controller_scenery(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/controllers/scenery')
                    ->assertSee('Scenery');
        });
    }

    public function test_controller_merch_store(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/controllers/merch')
                    ->assertSee('Merch Store');
        });
    }

    public function test_controller_stats(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/controllers/stats')
                    ->assertSee('ARTCC Controller Statistics');
        });
    }

    public function test_controller_incident_report(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/controllers/incident/report')
                    ->assertSee('New Incident Report');
        });
    }

    public function test_controller_logout(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/logout')
                    ->assertSee('You have been successfully logged out');
        });
    }
}
