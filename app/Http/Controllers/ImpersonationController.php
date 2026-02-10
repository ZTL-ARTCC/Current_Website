<?php

namespace App\Http\Controllers;

use App\Audit;
use App\User;
use Illuminate\Http\Request;

class ImpersonationController extends Controller {
    public function start(Request $request) {
        $user = User::find($request->user_id);
        if (is_null($user)) {
            return redirect()->back()->with('error', 'That user does not exist');
        }

        session()->put('impersonate', $user->id);

        Audit::newAudit('started impersonating user ' . $user->impersonation_name . '.');
        return redirect('/dashboard')->with('warning', 'Successfully started impersonationg ' . $user->full_name . '. CAUTION: Impersonating actively logs you into the user\'s REAL account. Changes made while impersonating will be reflected on the user\'s actual account. PROCEED WITH CARE.');
    }

    public function stop() {
        Audit::newAudit('impersonation session ending...');

        session()->forget('impersonate');
        session()->forget('impersonating_user');

        return redirect('/dashboard');
    }
}
