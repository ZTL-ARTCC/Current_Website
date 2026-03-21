<?php

namespace App\Mail;

use App\Enums\Queues;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

abstract class MailQueue extends Mailable implements ShouldQueue {
    use Queueable;

    public function __construct() {
        $this->queue = Queues::MAIL->value;
    }
}
