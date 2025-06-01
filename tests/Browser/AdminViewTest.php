<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Authentication;
use Tests\DuskTestCase;

class AdminViewTest extends DuskTestCase {

    public function test_admin_calendar(): void {
        $this->browse(function (Browser $browser) {
            Authentication::login($browser);
            $browser->visit('/dashboard/admin/calendar')
                    ->assertSee('Calendar/News');
        });
    }

    public function test_admin_new_calendar(): void {
        $this->browse(function (Browser $browser) {
            Authentication::login($browser);
            $browser->visit('/dashboard/admin/calendar/new')
                    ->assertSee('New Calendar Event/News');
        });
    }
    
    public function test_admin_airports(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/airports')
                    ->assertSee('Airport Management');
        });
    }

    public function test_admin_scenery(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/scenery')
                    ->assertSee('Scenery Management');
        });
    }

    public function test_admin_merch_store(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/store')
                    ->assertSee('Merch Store');
        });
    }

    public function test_admin_new_scenery(): void {
        $this->browse(function (Browser $browser) {

            $browser->visit('/dashboard/admin/scenery/new')
                    ->assertSee('New Scenery');
        });
    }

    public function test_admin_feedback(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/feedback')
                    ->assertSee('Feedback Management');
        });
    }

    public function test_admin_email(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/email/send')
                    ->assertSee('Send New Email');
        });
    }

    public function test_admin_announcement(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/announcement')
                    ->assertSee('Announcement');
        });
    }

    public function test_admin_incident_report(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/incident')
                    ->assertSee('Incident Report Management');
        });
    }

    public function test_admin_award_management(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/bronze-mic')
                    ->assertSee('Award Management');
        });
    }

    public function test_admin_live_event_info(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/live')
                    ->assertSee('Live Event Information');
        });
    }

    public function test_admin_audits(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/audits')
                    ->assertSee('Website Activity Audit');
        });
    }

    public function test_admin_task_monitor(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/monitor')
                    ->assertSee('Background Task Monitor');
        });
    }

    public function test_admin_feature_toggles(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/toggles')
                    ->assertSee('Feature Toggles');
        });
    }

    public function test_admin_create_feature_toggles(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/toggles/create')
                    ->assertSee('New Feature Toggle');
        });
    }

    public function test_admin_events_denylist(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/events/denylist')
                    ->assertSee('Event Denylist');
        });
    }

    public function test_admin_visit_requests(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/roster/visit/requests')
                    ->assertSee('Visit Requests');
        });
    }

    public function test_admin_roster_purge(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/admin/roster/purge-assistant')
                    ->assertSee('Roster Purge Assistant');
        });
    }
}
