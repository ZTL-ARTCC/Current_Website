<?php

namespace Tests\Browser;

use App\PilotPassportAward;
use App\PilotPassportEnrollment;
use App\PilotPassportLog;
use App\RealopsPilot;
use Config;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PilotPassportChallengeTest extends DuskTestCase {

    const TEST_AIRPORT_ID = 'GSO';
    const TEST_CHALLENGE_ID = 1;

    public function test_home_public(): void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilot_passport')
                    ->assertSee('What is the ZTL Pilot Passport Challenge?');
        });
    }

    public function test_home_authenticated(): void {
        $this->loginSamplePilot();
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilot_passport')
                    ->assertSee('Welcome, ');
        });
    }
    
    public function test_enrollments_view(): void {
        $this->loginSamplePilot();
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilot_passport')
                    ->clickLink('Enrollments')
                    ->assertSee('Select a path below and enroll to get started.');
        });
    }

    public function test_enroll(): void {
        $this->loginSamplePilot();
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilot_passport')
            ->clickLink('Enrollments')
            ->press('@enroll_1')
            ->assertSee('You are now enrolled in the ZTL Pilot Passport program!');
        });
        $enrollment = PilotPassportEnrollment::where('cid', Config::get('vatsim.auth_dev_credential'))->where('challenge_id', 1)->count();
        $this->assertTrue($enrollment > 0);
    }

    public function test_passport_book_view(): void {
        $this->loginSamplePilot(true);
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilot_passport')
                    ->clickLink('Passport Book')
                    ->assertSee('Visited on: ');
        });
    }

    public function test_achievements_view(): void {
        $this->loginSamplePilot(true);
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilot_passport')
                    ->clickLink('Achievements')
                    ->assertSee('Completed on: ');
        });
    }

    public function test_settings_view(): void {
        $this->loginSamplePilot();
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilot_passport')
                    ->clickLink('Settings')
                    ->assertSee('ZTL understands that some pilots may want to participate');
        });
    }

    public function test_change_privacy_settings(): void {
        $this->loginSamplePilot();
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilot_passport')
                    ->clickLink('Settings')
                    ->radio('@privacy', '2')
                    ->press('Save Settings')
                    ->visit('/pilot_passport')
                    ->clickLink('Settings')
                    ->assertRadioSelected('@privacy', '2');
        });
        $privacy_flag = RealopsPilot::where('id', Config::get('vatsim.auth_dev_credential'))->where('privacy', 2)->count();
        $this->assertTrue($privacy_flag > 0);
    }

    public function test_disenroll_purge(): void {
        $this->loginSamplePilot(true);
        $this->browse(function (Browser $browser) {
            $browser->visit('/pilot_passport')
                    ->clickLink('Settings')
                    ->press('@purge_data')
                    ->whenAvailable('.modal', function (Browser $modal) {
                        $modal->type('@confirm', 'confirm - purge all')
                            ->press('Continue');
                    })
                    ->waitFor('.navbar', 30)
                    ->assertSee('You have been successfully logged out');
            $cid = Config::get('vatsim.auth_dev_credential');
            $pilot = RealopsPilot::find($cid);
            $enrollment = PilotPassportEnrollment::where('cid', $cid)->first();
            $achievement = PilotPassportAward::where('cid', $cid)->first();
            $log = PilotPassportLog::where('cid', $cid)->first();
            $data_remaining = $pilot || $enrollment || $achievement || $log;
            $this->assertFalse($data_remaining);
        });
    }

    public function loginSamplePilot($with_enrollment = false): void {
        $this->checkSamplePilot($with_enrollment);
        $this->browse(function (Browser $browser) {
            $browser->loginAs(Config::get('vatsim.auth_dev_credential'), 'realops');
        });
    }

    public function checkSamplePilot($with_enrollment): void {
        $u = RealopsPilot::find(Config::get('vatsim.auth_dev_credential'));
        if (!$u) {
            $u = new RealopsPilot;
            $u->id = Config::get('vatsim.auth_dev_credential');
            $u->fname = 'Web';
            $u->lname = 'Two';
            $u->email = 'wm@ztlartcc.org';
            $u->save();
        }
        $this->clearSamplePilotActivity($u->id);
        $this->addSamplePilotAccomplishments($u->id);
        if ($with_enrollment) {
            $this->addSamplePilotEnrollment($u->id);
        }
    }

    public function clearSamplePilotActivity($cid): void {
        PilotPassportEnrollment::where('cid', $cid)->delete();
        PilotPassportLog::where('cid', $cid)->delete();
        PilotPassportAward::where('cid', $cid)->delete();
    }

    public function addSamplePilotAccomplishments($cid): void {
        if (!PilotPassportLog::where('cid', $cid)->exists()) {
            $l = new PilotPassportLog;
            $l->id = 1;
            $l->cid = $cid;
            $l->airfield = self::TEST_AIRPORT_ID;
            $l->visited_on = date('Y-m-d H:i:s');
            $l->callsign = 'RPA3573';
            $l->aircraft_type = 'E75L';
            $l->save();
        }
        if (!PilotPassportAward::where('cid', $cid)->exists()) {
            $a = new PilotPassportAward;
            $a->id = 1;
            $a->cid = $cid;
            $a->challenge_id = self::TEST_CHALLENGE_ID;
            $a->awarded_on = date('Y-m-d H:i:s');
            $a->save();
        }
    }

    public function addSamplePilotEnrollment($cid): void {
        if (!PilotPassportEnrollment::where('cid', $cid)->where('challenge_id', self::TEST_CHALLENGE_ID)->exists()) {
            $l = new PilotPassportEnrollment;
            $l->cid = $cid;
            $l->challenge_id = self::TEST_CHALLENGE_ID;
            $l->save();
        }
    }
}
