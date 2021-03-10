<?php

namespace App\Console\Commands;

use App\SoloCert;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Mail;
use Illuminate\Console\Command;

class UpdateSoloCerts extends Command
{
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
        $client = new Client();
        $res = $client->request('GET', 'https://api.vatusa.net/v2/solo');
        $solo_certs = json_decode($res->getBody());

        foreach($solo_certs->data as $s) {
            if(! ($s === true || $s === false)) {
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

        foreach($certs as $c) {
            if($c->expiration <= $today && $c->status == 0) {
                Mail::send('emails.solo_expire', ['c' => $c], function($message){
                    $message->from('solocerts@notams.ztlartcc.org', 'ZTL Solo Certifications')->subject('Solo Certification Expired');
                    $message->to('ta@ztlartcc.org');
                });
                $c->status = 1;

                $user = User::find($c->cid);

                if($c->pos == 0) {
                    $user->twr = 0;
                } elseif($c->pos == 1) {
                    $user->app = 0;
                } elseif($c->pos == 2) {
                    $user->ctr = 0;
                }
                $user->save();
                $c->save();
            }
        }
    }
}
