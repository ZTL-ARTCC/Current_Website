<?php

namespace App\Console\Commands;

use App\Mail\SoloCertExpiration;
use App\SoloCert;
use App\User;
use Carbon\Carbon;
use Config;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Mail;

class UpdateSoloCerts extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SoloCerts:UpdateSoloCerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates solo certs. Runs daily.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $client = new Client();
        $res = $client->request('GET', Config::get('vatusa.base').'/v2/solo');
        $solo_certs = json_decode($res->getBody());

        foreach ($solo_certs->data as $s) {
            if (! ($s === true || $s === false)) {
                if ($s->position == 'ATL_CTR') {
                    $current_cert = SoloCert::where('cid', $s->cid)->where('status', 0)->first();
                    if (!$current_cert) {
                        $cert = new SoloCert;
                        $cert->cid = $s->cid;
                        $cert->pos = 2;
                        $cert->expiration = $s->expires;
                        $cert->status = 0;
                        $cert->save();

                        $user = User::find($s->cid);
                        $user->ctr = 99;
                        $user->save();
                    }
                } elseif (substr($s->position, -3) == 'APP') {
                    $hcontrol = User::where('visitor', 0)->get();
                    foreach ($hcontrol as $h) {
                        if ($s->cid == $h->id) {
                            $current_cert = SoloCert::where('cid', $s->cid)->where('status', 0)->first();
                            if (!$current_cert) {
                                $cert = new SoloCert;
                                $cert->cid = $s->cid;
                                $cert->pos = 1;
                                $cert->expiration = $s->expires;
                                $cert->status = 0;
                                $cert->save();

                                $user = $h;
                                $user->app = 99;
                                $user->save();
                            }
                        }
                    }
                }
            }
        }

        $today = strval(Carbon::now()->subDay());
        $today = substr($today, 0, 10);
        $certs = SoloCert::get();

        foreach ($certs as $cert) {
            if ($cert->expiration <= $today && $cert->status == 0) {
                Mail::to('ta@ztlartcc.org')->send(new SoloCertExpiration($cert));
                $cert->status = 1;

                $user = User::find($cert->cid);

                if ($cert->pos == 0) {
                    $user->twr = 0;
                } elseif ($cert->pos == 1) {
                    $user->app = 0;
                } elseif ($cert->pos == 2) {
                    $user->ctr = 0;
                }
                $user->save();
                $cert->save();
            }
        }
    }
}
