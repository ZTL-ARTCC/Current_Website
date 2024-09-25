<?php

namespace Tests;

use App\User;
use Config;

class Authentication {
    /**
     * Handles authentication and user for test case.
     */
    public static function checkTestUser(): void {
        if (!User::where('id', Config::get('vatsim.auth_dev_credential'))->exists()) {
            $u = new User;
            $u->id = Config::get('vatsim.auth_dev_credential');
            $u->fname = 'Test';
            $u->lname = 'User';
            $u->email = 'test@ztlartcc.org';
            $u->rating_id = 7;
            $u->visitor = 0;
            $u->status = 1;
            $u->save();
        }
    }

    public static function login(&$browser): void {
        SELF::checkTestUser();
        $browser->loginAs(User::find(Config::get('vatsim.auth_dev_credential')));
    }
}
