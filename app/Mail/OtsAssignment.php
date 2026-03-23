<?php

namespace App\Mail;

use App\Mail\Package\ZTLAddress;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtsAssignment extends MailQueue {
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $ots, public $controller, public $ins) {
        parent::__construct();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            from: new ZTLAddress('ots-center', 'vZTL ARTCC OTS Center'),
            replyTo: [
                new Address($this->controller->email, $this->controller->full_name)
            ],
            subject: 'You Have Been Assigned an OTS for ' . $this->controller->full_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'emails.ots_assignment',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array {
        return [];
    }
}
