<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PublicViewTest extends DuskTestCase {
    /*
    Basic browser test - ensure that each page loads.
    This is a planned interim step - this should be built out to full capability later.
    */
    public function test_does_view_load(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Atlanta Virtual ARTCC');
            $browser->visit('https://www.aviationapi.com/charts')
                    ->pause(1000)
                    ->assetPathIs('https://www.aviationapi.com/charts')
                    ->assertDontSee('404');
            $browser->visit('https://www.aviationweather.gov/')
                    ->pause(1000)
                    ->assetPathIs('https://www.aviationweather.gov/')
                    ->assertDontSee('404');
            $browser->visit('/pilots/scenery')
                    ->assertSee('Scenery');
            $browser->visit('/pilots/airports')
                    ->assertSee('Airports');
            $browser->visit('/pilots/request-staffing')
                    ->assertSee('ZTL ARTCC Staffing Request');
            $browser->visit('http://www.flightaware.com/statistics/ifr-route/')
                    ->pause(1000)
                    ->assetPathIs('http://www.flightaware.com/statistics/ifr-route/')
                    ->assertDontSee('404');
            $browser->visit('/pilots/guide/atl')
                    ->assertSee('Atlanta Pilot Guide');
            $browser->visit('/ramp-status/atl')
                    ->assertSee("Atlanta Hartsfield Jackson Int'l Airport (ATL) Ramp/Gate Status");
            $browser->visit('/ramp-status/clt')
                    ->assertSee("Charlotte Douglas Int'l Airport (CLT) Ramp/Gate Status");
            $browser->visit('/controllers/roster')
                    ->assertSee('Roster');
            $browser->visit('/controllers/staff')
                    ->assertSee('Staff');
            $browser->visit('/controllers/files')
                    ->assertSee('ARTCC Files');
            $browser->visit('https://ids.ztlartcc.org/')
                    ->pause(1000)
                    ->assetPathIs('https://ids.ztlartcc.org/')
                    ->assertDontSee('404');
            $browser->visit('/controllers/stats')
                    ->assertSee('ARTCC Controller Statistics');
            $browser->visit('/controllers/teamspeak')
                    ->assertSee('ZTL ARTCC Teamspeak Information');
            $browser->visit('/privacy')
                    ->assertSee('ZTL ARTCC Privacy Policy & Terms and Conditions');
            $browser->visit('/visit')
                    ->assertSee('Visit ZTL ARTCC');
            $browser->visit('/feeback/new')
                    ->assertSee('Leave New Feedback');
            $browser->visit('/trainer_feeback/new')
                    ->assertSee('Leave New Training Team Feedback');
            $browser->visit('/login')
                    ->assertSee('Atlanta Virtual ARTCC wants to access your VATSIM account.');
            $browser->visit('https://www.teamspeak.com/?tour=yes')
                    ->pause(1000)
                    ->assetPathIs('https://www.teamspeak.com/?tour=yes')
                    ->assertDontSee('404');
            $browser->visit('https://www.thepilotclub.org/')
                    ->pause(1000)
                    ->assetPathIs('https://www.thepilotclub.org/')
                    ->assertDontSee('404');
            $browser->visit('http://www.vatsim.net/')
                    ->pause(1000)
                    ->assetPathIs('http://www.vatsim.net/')
                    ->assertDontSee('404');
            $browser->visit('http://www.vatusa.net/')
                    ->pause(1000)
                    ->assetPathIs('http://www.vatusa.net/')
                    ->assertDontSee('404');
        });
    }
}
