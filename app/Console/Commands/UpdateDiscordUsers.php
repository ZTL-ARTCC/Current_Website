<?php

namespace App\Console\Commands;

use App\ControllerLog;
use App\DiscordUser;
use App\User;
use Illuminate\Console\Command;

class UpdateDiscordUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Update:DiscordUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the discord user database.';

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
        $all_users = DiscordUser::get();

        // Get controller stats
        $year = date('y');
        $month = date('n');
        $stats = ControllerLog::aggregateAllControllersByPosAndMonth($year, $month);

        foreach($all_users as $u) {
            $user = User::find($u->cid);
            if($user->status == 2) {
                $u->delete();
            } else {
                $u->rating_id = $user->rating_id;
                $u->online_time_month = $stats[$user->id]->total_hrs;
                $u->save();
            }
        }
    }
}
