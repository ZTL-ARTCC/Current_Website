<?php

namespace App;

use App\File;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $fillable = ['id', 'name', 'type', 'desc', 'path', 'created_at', 'updated_at'];

    public static $WordType = [
        0 => 'VRC',
        1 => 'vSTARS',
        2 => 'vERAM',
        3 => 'vATIS',
        4 => 'SOPs',
        5 => 'LOAs',
        6 => 'Staff'
    ];

    public function getWordTypeAttribute() {
        foreach (File::$WordType as $id => $wordType) {
            if ($this->type == $id) {
                return $wordType;
            }
        }

        return "";
    }
}
