<?php

namespace App\Mail;

use App\Mail\Package\ZTLAddress;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Realops extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    private static $SUBJECTS = [
        'flight_cancelled' => 'Flight Cancelled',
        'bid' => 'Bid Confirmation',
        'cancel_bid' => 'Bid Cancelled',
        'assigned_flight' => 'Assignment Confirmation',
        'removed_from_flight' => 'Unassignment Confirmation',
        'flight_updated' => 'Flight Updated'
    ];

    /**
     * Create a new message instance.
     */
    public function __construct(public $flight, public $pilot, public $type, public $flight_number = '') {
        $this->flight_number = $this->flight->flight_number;

        if ($type == 'flight_cancelled') {
            $this->flight = null;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            from: new ZTLAddress("realops", "ZTL Realops"),
            subject: $this->generateSubject()
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'emails.realops.' . $this->type,
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

    private function generateSubject() {
        return 'Realops Flight ' . $this->flight_number . ' - ' .  $this::$SUBJECTS[$this->type];
    }

}
