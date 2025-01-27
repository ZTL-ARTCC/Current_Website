<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model {
    protected $table = 'files';
    protected $fillable = ['id', 'name', 'type', 'desc', 'path', 'disp_order', 'permalink', 'created_at', 'updated_at', 'row_separator'];

    public static $WordType = [
        3 => 'vATIS',
        4 => 'SOPs',
        5 => 'LOAs',
        6 => 'Staff',
        7 => 'Training',
        8 => 'Marketing'
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
