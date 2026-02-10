<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ImpersonationController extends Controller
{
    public function start(Request $request) {
        $user = User::find($request->user_id);
        if (is_null($user)) {
            return redirect()->back()->with('error', 'That user does not exist');
        }

        session()->put('impersonate', $user->id);
        return redirect('/dashboard');
    }

    public function stop() {
        session()->forget('impersonate');
        return redirect('/dashboard');
    }
}
