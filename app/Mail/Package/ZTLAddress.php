<?php

namespace App\Mail\Package;

use Config;
use Illuminate\Mail\Mailables\Address;

class ZTLAddress extends Address {
    public function __construct(?string $address = 'info', ?string $name = 'ZTL Web Admin') {
        $this->address = $address . Config::get('mail.from.domain');
        $this->name = $name;
    }
}
