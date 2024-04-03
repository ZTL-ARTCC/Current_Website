<?php

namespace App\Http\Controllers;

use App\User;

class RosterController extends Controller {
    public function index() {
        $hcontrollers_public = User::where('visitor', '0')->where('status', '1')->where('name_privacy', '0')->orderBy('lname', 'ASC')->get();
        $hcontrollers_private = User::where('visitor', '0')->where('status', '1')->where('name_privacy', '1')->orderBy('id', 'ASC')->get();
        $hcontrollers = $hcontrollers_public->merge($hcontrollers_private);
        $vcontrollers_public = User::where('visitor', '1')->where('status', '1')->where('name_privacy', '0')->orderBy('lname', 'ASC')->get();
        $vcontrollers_private = User::where('visitor', '1')->where('status', '1')->where('name_privacy', '1')->orderBy('id', 'ASC')->get();
        $vcontrollers = $vcontrollers_public->merge($vcontrollers_private);
 
        return view('site.roster')->with('hcontrollers', $hcontrollers)->with('vcontrollers', $vcontrollers);
    }

    public function staffIndex() {
        $users = User::with('roles')->get();

        $atm = $users->filter(function ($user) {
            return $user->hasRole('atm');
        });

        $datm = $users->filter(function ($user) {
            return $user->hasRole('datm');
        });

        $ta = $users->filter(function ($user) {
            return $user->hasRole('ta');
        });

        $ata = $users->filter(function ($user) {
            return $user->hasRole('ata');
        });

        $wm = $users->filter(function ($user) {
            return $user->hasRole('wm');
        });

        $awm = $users->filter(function ($user) {
            return $user->hasRole('awm');
        });

        $ec = $users->filter(function ($user) {
            return $user->hasRole('ec');
        });

        $aec = $users->filter(function ($user) {
            return $user->hasRole('aec');
        });

        $fe = $users->filter(function ($user) {
            return $user->hasRole('fe');
        });

        $afe = $users->filter(function ($user) {
            return $user->hasRole('afe');
        });

        $ins = $users->filter(function ($user) {
            return $user->hasRole('ins');
        });

        $mtr = $users->filter(function ($user) {
            return $user->hasRole('mtr');
        });

        return view('site.staff')->with('atm', $atm)->with('datm', $datm)
                                 ->with('ta', $ta)->with('ata', $ata)
                                 ->with('wm', $wm)->with('awm', $awm)
                                 ->with('ec', $ec)->with('aec', $aec)
                                 ->with('fe', $fe)->with('afe', $afe)
                                 ->with('ins', $ins)->with('mtr', $mtr);
    }
}
