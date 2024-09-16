<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ConsoleUploadTrainingTicketsTest extends TestCase {
    #[Test]
    public function it_has_command(): void {
        $this->assertTrue(class_exists(\App\Console\Commands\UploadTrainingTickets::class));
    }

    #[Test]
    public function it_runs_successfully(): void {
        $this->artisan('VATUSATrainingTickets:UploadPending')->assertSuccessful();
    }
}
