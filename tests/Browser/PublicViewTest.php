<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PublicViewTest extends DuskTestCase {

    public function test_public_home(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Atlanta Virtual ARTCC');
        });
    }

    public function test_public_scenery(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilots/scenery')
                    ->assertSee('Scenery');
        });
    }

    public function test_public_airports(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilots/airports')
                    ->assertSee('Airports');
        });
    }

    public function test_staffing_request(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilots/request-staffing')
                    ->assertSee('ZTL ARTCC Staffing Request');
        });
    }

    public function test_atl_pilot_guide(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilots/guide/atl')
                    ->assertSee('Atlanta Pilot Guide');
        });
    }

    public function test_atl_ramp_status(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/ramp-status/atl')
                    ->assertSee("Atlanta Hartsfield Jackson Int'l Airport (ATL) Ramp/Gate Status");
        });
    }

    public function test_clt_ramp_status(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/ramp-status/clt')
                    ->assertSee("Charlotte Douglas Int'l Airport (CLT) Ramp/Gate Status");
        });
    }

    public function test_public_controller_roster(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/controllers/roster')
                    ->assertSee('Roster');
        });
    }

    public function test_staff_list_view(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/controllers/staff')
                    ->assertSee('Staff');
        });
    }

    public function test_public_files(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/controllers/files')
                    ->assertSee('ARTCC Files');
        });
    }

    public function test_public_controller_stats(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/controllers/stats')
                    ->assertSee('ARTCC Controller Statistics');
        });
    }

    public function test_teamspeak_information(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/controllers/teamspeak')
                    ->assertSee('ZTL ARTCC Teamspeak Information');
        });
    }

    public function test_privacy_information(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/privacy')
                    ->assertSee('ZTL ARTCC Privacy Policy & Terms and Conditions');
        });
    }

    public function test_visitor_form(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/visit')
                    ->assertSee('Visit ZTL ARTCC');
        });
    }

    public function test_controller_feedback_form(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/feedback/new')
                    ->assertSee('Leave New Feedback');
        });
    }

    public function test_training_team_feedback_form(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/trainer_feedback/new')
                    ->assertSee('Leave New Training Team Feedback');
        });
    }
}
