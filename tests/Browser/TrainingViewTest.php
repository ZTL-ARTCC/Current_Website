<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Authentication;
use Tests\DuskTestCase;

class TrainingViewTest extends DuskTestCase {
    /*
    Basic browser test - ensure that each page loads.
    This is a planned interim step - this should be built out to full capability later.
    */
    public function test_does_view_load(): void {
        $this->browse(function (Browser $browser) {
            Authentication::login($browser);
            $browser->visit('/dashboard/training/info')
                    ->assertSee('Training Information');
            $browser->visit('/dashboard/training/atcast')
                    ->assertSee('ATCast Videos');
            $browser->visit('/dashboard/training/tickets')
                    ->assertSee('Training Tickets');
            $browser->visit('/dashboard/training/tickets/new')
                    ->assertSee('Submit New Training Ticket');
            $browser->visit('/dashboard/training/ots-center')
                    ->assertSee('OTS Center');
            $browser->visit('/dashboard/training/statistics')
                    ->assertSee('Training Department Dashboard');
        });
    }

    public function test_external_links(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://scheduling.ztlartcc.org')
                ->assertDontSee('404');
            $browser->visit('https://scheduling.ztlartcc.org/index.php/user/login')
                ->assertDontSee('404');
        });
    }
}
