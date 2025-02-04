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

class VisitorMail extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    private static $SUBJECTS = [
        'new' => 'New Visitor Request Submitted',
        'accept' => 'Visitor Request Accepted',
        'reject' => 'Visitor Request Rejected',
        'remove' => 'Notification of ZTL Roster Removal'
    ];

    /**
     * Create a new message instance.
     */
    public function __construct(public $type, public $visitor) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        $datm = new Address('datm@ztlartcc.org', 'vZTL ARTCC DATM');
        return new Envelope(
            from: new ZTLAddress('visitors', 'vZTL ARTCC Visiting Department'),
            replyTo: [ $datm ],
            cc: [ $datm ],
            subject: $this::$SUBJECTS[$this->type]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'emails.visit.' . $this->type,
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
