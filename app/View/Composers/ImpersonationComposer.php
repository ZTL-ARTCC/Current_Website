<?php

namespace App\View\Composers;

use App\User;
use Auth;
use Illuminate\View\View;

class ImpersonationComposer {
    /**
     * Create a new profile composer.
     */
    public function __construct(
    ) {
    }
 
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void {
        $users = null;
        $is_impersonating = session()->has('impersonate');

        if (Auth::user()->isAbleTo('snrStaff')) {
            $users = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('impersonation_name', 'id');
        }

        $view->with('users', $users)->with('is_impersonating', $is_impersonating);
    }
}
