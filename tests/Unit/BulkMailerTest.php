<?php

namespace Tests\Unit;

use App\Mail\SendEmail;
use App\User;
use Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\Authentication;
use Tests\TestCase;

class BulkMailerTest extends TestCase {
    private $subject = 'Test Bulk Email';
    private $body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
    private $reply_to = 'info@notams.ztlartcc.org';
    private $name = 'ZTL Web Admin';

    public function test_bulk_mailer() {
        Authentication::checktestUser();
        $sender = User::find(Config::get('vatsim.auth_dev_credential'));
        $this->actingAs($sender);

        Mail::fake();

        $emails_sent = 0;
        $recipients = User::all()->pluck('email');
        foreach ($recipients as $recipient) {
            if ($recipient != 'No email') {
                Mail::to($recipient)->send(new SendEmail($sender, $this->subject, $this->body, $this->reply_to, $this->name));
                $emails_sent++;
            }
        }

        Mail::assertQueuedCount($emails_sent);

        Mail::assertQueued(SendEmail::class, function ($mail) {
            return $mail->subject === $this->subject &&
                   Str::contains($mail->render(), $this->body);
        });
    }
}
