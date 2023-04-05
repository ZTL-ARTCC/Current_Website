<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model {
    protected $table = 'audits';
    protected $fillable = ['id', 'cid', 'ip', 'what', 'created_at', 'updated_at'];

    public function getTimeDateAttribute() {
        $date = $this->created_at;
        $new_date = $date->format('m/d/Y').' at '.substr($date, 11, 5);

        return $new_date;
    }

    public static function newAudit(string $message): void {
        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name . ' ' . $message;
        $audit->save();
    }
}
