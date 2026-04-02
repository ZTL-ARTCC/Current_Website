<?php

namespace App\Mail;

use App\Mail\Package\ZTLAddress;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentComment extends MailQueue {
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $trainer_name, public $ticket_id) {
        parent::__construct();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            from: new ZTLAddress('feedback', 'vZTL ARTCC Training Department'),
            subject: 'You Have a New Student Comment!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'emails.student_comment',
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
