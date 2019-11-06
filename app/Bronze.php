<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Bronze extends Model
{
    protected $table = 'bronze_mic';
    protected $fillable = ['id', 'controller_id', 'month', 'year', 'month_hours', 'updated_at', 'created_at'];

    public function getNameAttribute() {
        $user = User::find($this->controller_id);

        return $user->full_name;
    }
}
