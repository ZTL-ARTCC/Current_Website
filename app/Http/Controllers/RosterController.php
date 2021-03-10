<?php

namespace App\Http\Controllers;

use App\Opt;
use App\Role;
use App\User;
use Carbon\Carbon;
use Config;
use DB;
use Eloquent\Collection;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Session;

class RosterController extends Controller
{
    public function index() {
        $hcontrollers = User::where('visitor', '0')->where('status', '1')->orderBy('lname', 'ASC')->get();
        $vcontrollers = User::where('visitor', '1')->where('status', '1')->where('visitor_from', '!=', 'ZJX')->orderBy('lname', 'ASC')->get();
        $visagreecontrollers = User::where('visitor', '1')->where('visitor_from', 'ZJX')->orderBy('visitor_from', 'ASC')->orderBy('lname', 'ASC')->get();

        return view('site.roster')->with('hcontrollers', $hcontrollers)->with('vcontrollers', $vcontrollers)->with('visagreecontrollers', $visagreecontrollers);
    }

    public function login() {
        if(Auth::check()) {
            return redirect('/')->with('error', 'You are already logged in.');
        }
        if(Config::get('app.debug') == false) {
            if(!Auth::check() && !isset($_GET['token'])) {
                $_SESSION['redirect'] = Config::get('app.url');
                header("Location: https://login.vatusa.net/uls/v2/login?fac=".Config::get('vatusa.facility'));
                exit;
            }
        } elseif(Config::get('app.url') == 'https://development.ztlartcc.org') {
            if(!Auth::check() && !isset($_GET['token'])) {
                $_SESSION['redirect'] = Config::get('app.url');
                header("Location: https://login.vatusa.net/uls/v2/login?fac=".Config::get('vatusa.facility')."&dev=1&url=2");
                exit;
            }
        } else {
            if(!Auth::check() && !isset($_GET['token'])) {
                $_SESSION['redirect'] = Config::get('app.url');
                header("Location: https://login.vatusa.net/uls/v2/login?fac=".Config::get('vatusa.facility')."&dev=1&url=3");
                exit;
            }
        }

        $token = $_GET['token'];
        $parts = explode('.', $token);

        $token = $this->base64url_decode($parts[1]);

        $jwk = json_decode(Config::get('vatusa.jwk'),   true);

        $algorithms = ['HS256' => 'sha256', 'HS384' => 'sha384', 'HS512' => 'sha512'];

        if (!isset($algorithms[$jwk['alg']])) {
            return redirect('/')->with('error', "Invalid Operation");
        }

        $sig = $this->base64url_encode(hash_hmac($algorithms[$jwk['alg']], "$parts[0].$parts[1]", $this->base64url_decode($jwk['k']), true));

        $signature = $this->base64url_decode($parts[1]);
        $json_token = json_decode($signature, true)['sig'];

        if($sig == $parts[2]) {

            $token = json_decode($token, true);

            $x = 0;
            Log::info("loginv2 at $x"); $x++;
            if($token['iss'] != 'VATUSA') {
                return redirect('/')->with('error', "Token not issued from VATUSA.");
            }
            if($token['aud'] != 'ZTL') {
                return redirect('/')->with('error', "Token not issued for ZTL.");
            }

            $client = new Client();
            $url = "https://login.vatusa.net/uls/v2/info?token={$parts[1]}";
            $result = $client->get($url);
            $res = json_decode($result->getBody()->__toString(), true);

            $userstatuscheck = User::find($res['cid']);
            if($userstatuscheck) {
                if($userstatuscheck->status != 2) {
                    // Save the user's rating for use down the line
                    $rating_old = $userstatuscheck->rating_id;

                    $userstatuscheck->fname = $res['firstname'];
                    $userstatuscheck->lname = $res['lastname'];
                    $userstatuscheck->email = $res['email'];
                    $userstatuscheck->rating_id = $res['intRating'];
                    $userstatuscheck->json_token = encrypt($json_token);
    				$client = new Client();
    				$response = $client->request('GET', 'https://api.vatusa.net/v2/user/'.$res['cid'].'?apikey='.Config::get('vatusa.api_key'));
    				$resu = json_decode($response->getBody());
    				if($resu->data->flag_broadcastOptedIn == 1) {
    					if($userstatuscheck->opt != 1) {
    						$opt = new Opt;
    						$opt->controller_id = $res['cid'];
    						$opt->ip_address = '0.0.0.0';
    						$opt->means = 'VATUSA API';
    						$opt->option = 1;
    						$opt->save();
    						$userstatuscheck->opt = 1;
    					}
    				} else {
                        $user_opt = Opt::where('controller_id', $userstatuscheck->id)->where('means', '!=', 'VATUSA API')->where('option', 1)->first();
    					if($userstatuscheck->opt != 0 && !isset($user_opt)) {
    						$opt = new Opt;
    						$opt->controller_id = $res['cid'];
    						$opt->ip_address = '0.0.0.0';
    						$opt->means = 'VATUSA API';
    						$opt->option = 0;
    						$opt->save();
    						$userstatuscheck->opt = 0;
    					}
                    }
                    if($userstatuscheck->visitor == '1') {
                        if($resu->data->facility != 'ZZN'){
                            $userstatuscheck->visitor_from = $resu->data->facility;
                        }
                    } else {
                        $userstatuscheck->visitor_from = null;
                    }
                    $userstatuscheck->save();

                    // Make sure the user is on Moodle
                    if(Config::get('app.moodle') == 1) {
                        $moodle = DB::table('mdl_user')->where('id', $userstatuscheck->id)->first();

                        // Check and see if the user is in Moodle
                        // If they are, update them
                        // If they aren't, add them
                        if($moodle) {
                            // Update the moodle user
                            // Makes sure the user isn't deleted in moodle and updates their email
                            DB::table('mdl_user')->where('id', $moodle->id)->update(['deleted' => 0]);
                            DB::table('mdl_user')->where('id', $moodle->id)->update(['email' => $userstatuscheck->email]);

                            // Check for mentor
                            $old_mtr_role = DB::table('mdl_role_assignments')->where('userid', $userstatuscheck->id)->where('roleid',  15);
                            if($old_mtr_role)
                                $old_mtr_role->delete();
                            if($userstatuscheck->hasRole('mtr')) {
                                $now = Carbon::now()->timestamp;
                                DB::table('mdl_role_assignments')->insert([
                                    'roleid' => 15,
                                    'contextid' => 1,
                                    'userid' => $userstatuscheck->id,
                                    'modifierid' => 1,
                                    'timemodified' => $now
                                ]);
                            }

                            // Check for staff
                            $all_staff_roles = DB::table('mdl_role_assignments')->where('roleid',  16)->orWhere('roleid', 17)->get();

                            // Go through each staff role and find the one that matches, if any
                            $old_staff_role = null;
                            foreach($all_staff_roles as $r) {
                                if($r->userid == $userstatuscheck->id)
                                    $old_staff_role = $r;
                            }

                            // Delete the old role
                            if($old_staff_role)
                                DB::table('mdl_role_assignments')->where('id', $old_staff_role->id)->delete();

                            if($userstatuscheck->can('snrStaff')) {
                                $now = Carbon::now()->timestamp;
                                DB::table('mdl_role_assignments')->insert([
                                    'roleid' => 17,
                                    'contextid' => 1,
                                    'userid' => $userstatuscheck->id,
                                    'modifierid' => 1,
                                    'timemodified' => $now
                                ]);
                            } elseif($userstatuscheck->can('staff')) {
                                $now = Carbon::now()->timestamp;
                                DB::table('mdl_role_assignments')->insert([
                                    'roleid' => 16,
                                    'contextid' => 1,
                                    'userid' => $userstatuscheck->id,
                                    'modifierid' => 1,
                                    'timemodified' => $now
                                ]);
                            }

                            //Assigns role in moodle database and adds the user to moodle
                            if ($rating_old != $userstatuscheck->rating_id) {
                                $old_role = DB::table('mdl_role_assignments')->where('userid', $userstatuscheck->id)->where('roleid', '!=', 15)->where('roleid', '!=', 16)->where('roleid', '!=', 17);
                                $old_role->delete();

                                if ($userstatuscheck->rating_id == 1) {
                                    $mdl_rating = 18;
                                } elseif ($userstatuscheck->rating_id == 2) {
                                    $mdl_rating = 9;
                                } elseif ($userstatuscheck->rating_id == 3) {
                                    $mdl_rating = 10;
                                } elseif ($userstatuscheck->rating_id == 4) {
                                    $mdl_rating = 11;
                                } elseif ($userstatuscheck->rating_id == 5) {
                                    $mdl_rating = 12;
                                } elseif ($userstatuscheck->rating_id == 7 || $userstatuscheck->rating_id == 11 || $userstatuscheck->rating_id == 12) {
                                    $mdl_rating = 13;
                                } elseif ($userstatuscheck->rating_id == 8 || $userstatuscheck->rating_id == 10) {
                                    $mdl_rating = 14;
                                } else {
                                    $mdl_rating = 0;
                                }

                                $now = Carbon::now()->timestamp;
                                DB::table('mdl_role_assignments')->insert([
                                    'roleid' => $mdl_rating,
                                    'contextid' => 1,
                                    'userid' => $userstatuscheck->id,
                                    'modifierid' => 1,
                                    'timemodified' => $now
                                ]);
                            }
                        } else {
                            //Adds user to moodle database
                            DB::table('mdl_user')->insert([
                                'id' => $userstatuscheck->id,
                                'confirmed' => 1,
                                'mnethostid' => 1,
                                'username' => $userstatuscheck->id,
                                'firstname' => $userstatuscheck->fname,
                                'lastname' => $userstatuscheck->lname,
                                'email' => $userstatuscheck->email
                            ]);

                            // Check for mentor
                            if($userstatuscheck->hasRole('mtr')) {
                                $now = Carbon::now()->timestamp;
                                DB::table('mdl_role_assignments')->insert([
                                    'roleid' => 15,
                                    'contextid' => 1,
                                    'userid' => $userstatuscheck->id,
                                    'modifierid' => 1,
                                    'timemodified' => $now
                                ]);
                            }

                            // Check for staff
                            if($userstatuscheck->can('snrStaff')) {
                                $now = Carbon::now()->timestamp;
                                DB::table('mdl_role_assignments')->insert([
                                    'roleid' => 17,
                                    'contextid' => 1,
                                    'userid' => $userstatuscheck->id,
                                    'modifierid' => 1,
                                    'timemodified' => $now
                                ]);
                            } elseif($userstatuscheck->can('staff')) {
                                $now = Carbon::now()->timestamp;
                                DB::table('mdl_role_assignments')->insert([
                                    'roleid' => 16,
                                    'contextid' => 1,
                                    'userid' => $userstatuscheck->id,
                                    'modifierid' => 1,
                                    'timemodified' => $now
                                ]);
                            }

                            //Assigns role in moodle database and adds the user to moodle
                            if ($rating_old != $userstatuscheck->rating_id) {
                                $old_role = DB::table('mdl_role_assignments')->where('userid', $userstatuscheck->id)->where('roleid', '!=', 15)->where('roleid', '!=', 16)->where('roleid', '!=', 17);
                                $old_role->delete();

                                if ($userstatuscheck->rating_id == 1) {
                                    $mdl_rating = 18;
                                } elseif ($userstatuscheck->rating_id == 2) {
                                    $mdl_rating = 9;
                                } elseif ($userstatuscheck->rating_id == 3) {
                                    $mdl_rating = 10;
                                } elseif ($userstatuscheck->rating_id == 4) {
                                    $mdl_rating = 11;
                                } elseif ($userstatuscheck->rating_id == 5) {
                                    $mdl_rating = 12;
                                } elseif ($userstatuscheck->rating_id == 7 || $userstatuscheck->rating_id == 11 || $userstatuscheck->rating_id == 12) {
                                    $mdl_rating = 13;
                                } elseif ($userstatuscheck->rating_id == 8 || $userstatuscheck->rating_id == 10) {
                                    $mdl_rating = 14;
                                } else {
                                    $mdl_rating = 0;
                                }

                                $now = Carbon::now()->timestamp;
                                DB::table('mdl_role_assignments')->insert([
                                    'roleid' => $mdl_rating,
                                    'contextid' => 1,
                                    'userid' => $userstatuscheck->id,
                                    'modifierid' => 1,
                                    'timemodified' => $now
                                ]);
                            }
                        }
                    }

                    Auth::loginUsingId($res['cid'], true);
                } else {
                    return redirect('/')->with('error', 'You have not been found on the roster. If you have recently joined, please allow up to an hour for the roster to update.');
                }
            } else {
                return redirect('/')->with('error', 'You have not been found on the roster. If you have recently joined, please allow up to an hour for the roster to update.');
            }

            if($userstatuscheck->status == 0){
                return redirect('/dashboard')->with('success', 'You have been logged in successfully. Please note that you are on an LOA and should not control until off the LOA. If this is an error, please let the DATM know.');
            } else {
                return redirect('/dashboard')->with('success', 'You have been logged in successfully.');
            }
        } else {
            return redirect('/')->with('error', 'Bad Signature.');
        }
    }

    public function logout() {
        if(!Auth::check()) {
            return redirect('/')->with('error', 'You are not logged in.');
        } else {
            if(Config::get('app.moodle') == 1)
                // Remove the user's Moodle password
                DB::table('mdl_user')->where('id', Auth::id())->update(['password' => 'LOGGED OUT']);

            Auth::logout();
            return redirect('/')->with('success', 'You have been logged out successfully.');
        }
    }

    public function base64url_encode($data, $use_padding = false) {
        $encoded = strtr(base64_encode($data), '+/', '-_');
        return true === $use_padding ? $encoded : rtrim($encoded, '=');
    }

    public function base64url_decode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public function staffIndex() {
        $users = User::with('roles')->get();

        $atm = $users->filter(function($user){
            return $user->hasRole('atm');
        });

        $datm = $users->filter(function($user){
            return $user->hasRole('datm');
        });

        $ta = $users->filter(function($user){
            return $user->hasRole('ta');
        });

        $ata = $users->filter(function($user){
            return $user->hasRole('ata');
        });

        $wm = $users->filter(function($user){
            return $user->hasRole('wm');
        });

        $awm = $users->filter(function($user){
            return $user->hasRole('awm');
        });

        $ec = $users->filter(function($user){
            return $user->hasRole('ec');
        });

        $aec = $users->filter(function($user){
            return $user->hasRole('aec');
        });

        $fe = $users->filter(function($user){
            return $user->hasRole('fe');
        });

        $afe = $users->filter(function($user){
            return $user->hasRole('afe');
        });

        $ins = $users->filter(function($user){
            return $user->hasRole('ins');
        });

        $mtr = $users->filter(function($user){
            return $user->hasRole('mtr');
        });

        return view('site.staff')->with('atm', $atm)->with('datm', $datm)
                                 ->with('ta', $ta)->with('ata', $ata)
                                 ->with('wm', $wm)->with('awm', $awm)
                                 ->with('ec', $ec)->with('aec', $aec)
                                 ->with('fe', $fe)->with('afe', $afe)
                                 ->with('ins', $ins)->with('mtr', $mtr);
    }
}
