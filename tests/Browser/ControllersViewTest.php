<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ControllersViewTest extends DuskTestCase
{
        /*
        Basic browser test - ensure that each page loads.
        This is a planned interim step - this should be built out to full capability later.
    */
        public function test_does_view_load(): void
        {
                $this->browse(function (Browser $browser) {
                        $browser->visit('/dashboard')
                                ->assertSee('Controller Dashboard');
                        $browser->visit('/dashboard/controllers/search-airport/search?apt=KATL')
                                ->assertSee('Airport Information');
                        $browser->visit('/dashboard/controllers/profile')
                                ->assertSee('My Profile');
                        $browser->visit('/dashboard/controllers/roster')
                                ->assertSee('Roster');
                        $browser->visit('/dashboard/controllers/events')
                                ->assertSee('Events');
                        $browser->visit('/dashboard/controllers/bookings')
                                ->assertSee('ATC Bookings');
                        $browser->visit('/dashboard/controllers/files')
                                ->assertSee('Files');
                        // vIDS link tested in PublicViewTest
                        $browser->visit('/dashboard/controllers/scenery')
                                ->assertSee('Scenery');
                        $browser->visit('/dashboard/controllers/stats')
                                ->assertSee('ARTCC Controller Statistics');
                        $browser->visit('/dashboard/controllers/incident/report')
                                ->assertSee('New Incident Report');
                        $browser->visit('/logout')
                                ->assertSee('You have been successfully logged out');
                });
        }
}
