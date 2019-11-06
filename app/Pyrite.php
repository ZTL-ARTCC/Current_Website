<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pyrite extends Model
{
    protected $table = 'pyrite_mic';
    protected $fillable = ['id', 'controller_id', 'year', 'year_hours', 'updated_at', 'created_at'];

    public function getNameAttribute() {
        $user = User::find($this->controller_id);

        return $user->full_name;
    }
}
