<?php

namespace App\Http\Controllers;

use App\Enums\SessionVariables;
use App\User;
use Illuminate\Http\Request;

class ImpersonationController extends Controller {
    public function start(Request $request) {
        $user = User::find($request->user_id);
        $is_impersonating = session()->has(SessionVariables::IMPERSONATE->value);
        if (is_null($user)) {
            return redirect()->back()->with(SessionVariables::ERROR->value, 'That user does not exist');
        }

        if ($is_impersonating) {
            return redirect()->back()->with(SessionVariables::ERROR->value, 'You must first stop impersonating your current user before beginning a new session');
        }

        session()->put(SessionVariables::IMPERSONATE->value, $user->id);
        return redirect('/dashboard')->with(SessionVariables::ERROR->value, 'Successfully started impersonationg ' . $user->full_name . '. CAUTION: Impersonating actively logs you into the user\'s REAL account. Changes made while impersonating will be reflected on the user\'s actual account. PROCEED WITH CARE.');
    }

    public function stop() {
        session()->forget(SessionVariables::IMPERSONATE->value);
        return redirect('/dashboard');
    }
}
