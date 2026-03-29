<?php

namespace App\Mail;

use App\Mail\Package\ZTLAddress;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PilotPassportMail extends MailQueue {
    use SerializesModels;

    private static $SUBJECTS = [
        'enroll' => 'ZTL Pilot Passport Program Enrollment',
        'visited_airfield' => 'ZTL Pilot Passport - Visit Recorded',
        'phase_complete' => 'ZTL Pilot Passport - Phase Complete!'
    ];

    /**
     * Create a new message instance.
     */
    public function __construct(public $type, public $pilot, public $data = null) {
        parent::__construct();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            from: new ZTLAddress('events', 'vZTL ARTCC Events Department'),
            replyTo: [
                new Address('wm@ztlartcc.org', 'vZTL ARTCC WM')
            ],
            subject: $this::$SUBJECTS[$this->type]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'emails.pilot_passport.' . $this->type,
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
