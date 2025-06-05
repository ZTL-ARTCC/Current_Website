<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use stdClass;

class Merch extends Model {
    protected $table = 'merch';
    public $timestamps = false;

    public function displayFlag(): null|stdClass {
        if (is_null($this->flag)) {
            return null;
        }
        $ret = new stdClass;
        $ret->badge = $ret->text = null;
        switch ($this->flag) {
            case 'coming soon':
                $ret->badge = 'primary';
                $ret->text = 'Coming Soon!';
                break;
            case 'limited stock':
                $ret->badge = 'warning';
                $ret->text = 'Limited Quantity Remaining!';
                break;
            case 'out of stock':
                $ret->badge = 'danger';
                $ret->text = 'Out of Stock';
                break;
            case 'new':
                $ret->badge = 'warning';
                $ret->text = 'New!';
                break;
            default: return null;
        }
        return $ret;
    }
}
