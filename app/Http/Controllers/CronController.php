<?php

namespace App\Http\Controllers;

use Artisan;
use Config;
use Illuminate\Http\Request;

class CronController extends Controller
{
    public function index(Request $request) {
        $job = $request->j;
        $token = $request->t;

        function command_exists($name) {
            return array_has(Artisan::all(), $name);
        }

        if($token == Config::get('cron.token')) {
            if($job != null) {
                if(command_exists($job)) {
                    Artisan::call($job);
                    return 'success';
                } else {
                    return 'error: That command does not exist.';
                }
            } else {
                return 'error: No command specified.';
            }
        } else {
            return 'error: Incorrect or no token specified.';
        }
    }
}
