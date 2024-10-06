<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Authentication;
use Tests\DuskTestCase;

class TrainingViewTest extends DuskTestCase {

    public function test_training_info(): void {
        $this->browse(function (Browser $browser) {
            Authentication::login($browser);
            $browser->visit('/dashboard/training/info')
                    ->assertSee('Training Information');
        });
    }

    public function test_training_atcast(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/training/atcast')
                    ->assertSee('ATCast Videos');
        });
    }

    public function test_training_tickets(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/training/tickets')
                    ->assertSee('Training Tickets');
            $browser->visit('/dashboard/training/tickets/new')
                    ->assertSee('Submit New Training Ticket');
        });
    }

    public function test_ots_center(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/training/ots-center')
                    ->assertSee('OTS Center');
        });
    }

    public function test_training_statistics(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard/training/statistics')
                    ->assertSee('Training Department Dashboard');
        });
    }
}
