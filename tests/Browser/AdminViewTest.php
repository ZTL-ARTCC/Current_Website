<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminViewTest extends DuskTestCase
{
        /*
        Basic browser test - ensure that each page loads.
        This is a planned interim step - this should be built out to full capability later.
    */
        public function test_does_view_load(): void
        {
                $this->browse(function (Browser $browser) {
                        $browser->visit('/dashboard/admin/calendar')
                                ->assertSee('Calendar/News');
                        $browser->visit('/dashboard/admin/calendar')
                                ->assertSee('New Calendar Event/News');
                        $browser->visit('/dashboard/admin/airports')
                                ->assertSee('Airport Management');
                        $browser->visit('/dashboard/admin/scenery')
                                ->assertSee('Scenery Management');
                        $browser->visit('/dashboard/admin/scenery/new')
                                ->assertSee('New Scenery');
                        $browser->visit('/dashboard/admin/feedback')
                                ->assertSee('Feedback Management');
                        $browser->visit('https://accounts.zoho.in/')
                                ->pause(1000)
                                ->assetPathIs('https://accounts.zoho.in/')
                                ->assertDontSee('404');
                        $browser->visit('/dashboard/admin/email/send')
                                ->assertSee('Send New Email');
                        $browser->visit('/dashboard/admin/announcement')
                                ->assertSee('Announcement');
                        $browser->visit('/dashboard/admin/incident')
                                ->assertSee('Incident Report Management');
                        $browser->visit('/dashboard/admin/bronze-mic')
                                ->assertSee('Award Management');
                        $browser->visit('/dashboard/admin/live')
                                ->assertSee('Live Event Information');
                        $browser->visit('/dashboard/admin/audits')
                                ->assertSee('Website Activity Audit');
                        $browser->visit('/dashboard/admin/monitor')
                                ->assertSee('Background Task Monitor');
                        $browser->visit('/dashboard/admin/toggles')
                                ->assertSee('Feature Toggles');
                        $browser->visit('/dashboard/admin/toggles/create')
                                ->assertSee('New Feature Toggle');
                        $browser->visit('/dashboard/admin/events/denylist')
                                ->assertSee('Event Denylist');
                        $browser->visit('/dashboard/admin/roster/visit/requests')
                                ->assertSee('Visit Requests');
                        $browser->visit('/dashboard/admin/roster/purge-assistant')
                                ->assertSee('Roster Purge Assistant');
                });
        }
}
