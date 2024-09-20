<?php

namespace App\Mail;

use App\Mail\Package\ZTLAddress;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BugReport extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $reporter, public $url, public $error, public $desc) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            from: new ZTLAddress('bugs', 'vZTL ARTCC Bugs'),
            replyTo: [
                new Address($this->reporter->email, $this->reporter->full_name)
            ],
            subject: 'New Bug Report',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'emails.bug',
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
