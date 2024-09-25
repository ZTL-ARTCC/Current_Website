<?php

namespace Tests\Browser;

use Config;
use Laravel\Dusk\Browser;
use Tests\Authentication;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase {
    /**
     * Test the login sequence.
     */
    public function testLogin(): void {
        Authentication::checkTestUser();
        $this->browse(function (Browser $browser) {
            $browser->visit('https://auth-dev.vatsim.net/login')
            ->assertSee('VATSIM ID')
            ->type('id', Config::get('vatsim.auth_dev_credential'))
            ->type('password', Config::get('vatsim.auth_dev_credential'))
            ->press('Sign in');
        });
    }
}
