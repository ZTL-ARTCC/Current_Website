<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;

class ValidateEmail {
    public function handle(MessageSending $event) {
        // Ensures 'to' email address is valid before attempting to send email
        $valid_to_addresses = [];
        foreach ($event->message->getTo() as $addressee) { // ['john@doe.com' => 'John Doe']
            $valid_email_addr = filter_var($addressee->getAddress(), FILTER_VALIDATE_EMAIL);
            if ($valid_email_addr !== false) {
                $valid_to_addresses[] = $addressee->getAddress();
            }
        }
        if (count($valid_to_addresses) == 0) {
            return false; // Stop the mailer
        } else {
            $event->message->to(...$valid_to_addresses);
            return $event;
        }
    }
}
