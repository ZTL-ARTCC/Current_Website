<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model {
    protected $table = 'audits';

    public function getTimeDateAttribute() {
        $date = $this->created_at;
        $new_date = $date->format('m/d/Y').' at '.substr($date, 11, 5);

        return $new_date;
    }

    public static function newAudit(string $message): void {
        $impersonated_by_id = null;
        $impersonation_string = '';
        if (session()->has('impersonating_user')) {
            $impersonated_by_id = session('impersonating_user');
            $impersonation_user = User::find($impersonated_by_id);

            $impersonation_string = 'IMPERSONATED BY ' . (is_null($impersonation_user) ? 'UNKNOWN' : $impersonation_user->full_name) . ': ';
        }
        $impersonated_by_id = session()->has('impersonating_user') ? session('impersonating_user') : null;

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->impersonated_by_id = $impersonated_by_id;
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = $impersonation_string . Auth::user()->full_name . ' ' . $message;
        $audit->save();
    }
}
