<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class dbreset extends Command {
    /**
     * @var string
     */
    protected $signature = 'db:reset';
    public function handle() {
        $this->call('db:wipe');
        $this->call('migrate');
        $this->call('db:seed');
        $this->info('Database successfully reset.');
    }
}
