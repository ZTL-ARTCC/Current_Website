<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;

class CheckEmailPreferences {
    public function handle(MessageSending $event) {
        // Ensures 'to' email address is valid before attempting to send email
        $to_address = $event->message->getTo(); // ['john@doe.com' => 'John Doe']
        $valid_to_address = [];
        for ($e=0; $e<count($to_address); $e++) {
            $valid_email_addr = filter_var(key($to_address[$e]), FILTER_VALIDATE_EMAIL);
            if ($valid_email_addr && !empty(key($to_address[$e]))) {
                $valid_to_address[$valid_email_addr] = $to_address[$e];
            }
        }
        if (count($valid_to_address) == 0) {
            return false; // Stop the mailer
        } else {
            $event->message->setTo($valid_to_address);
            return $event;
        }
    }
}
