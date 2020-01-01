<?php

namespace App\Console\Commands;

use App\User;
use DB;
use Illuminate\Console\Command;

class Moodle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MoodleUsers:Create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts users into the moodle database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::get();
        foreach($users as $u) {
            DB::table('mdl_user')->insert([
                 'id' => $u->id,
                 'confirmed' => 1,
                 'mnethostid' => 1,
                 'username' => $u->id,
                 'firstname' => $u->fname,
                 'lastname' => $u->lname,
                 'email' => $u->email
             ]);
        }
        
    }
}
