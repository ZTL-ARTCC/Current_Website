<?php

namespace App\View\Composers;

use App\Enums\SessionVariables;
use App\User;
use Auth;
use Illuminate\View\View;
use Laratrust\Models\Role;

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
        $is_impersonating = session()->has(SessionVariables::IMPERSONATE->value);

        if (Auth::user()->isAbleTo('snrStaff')) {
            $users = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('impersonation_name', 'id');
        }

        $view->with('users', $users)->with('is_impersonating', $is_impersonating);
    }
}
