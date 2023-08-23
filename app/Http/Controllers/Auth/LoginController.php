<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VatsimOAuthController;
use App\Opt;
use App\RealopsPilot;
use App\User;
use Config;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

/**
 * This controller handles authenticating users for the application and
 * redirecting them to your home screen. The controller uses a trait
 * to conveniently provide its functionality to your applications.
 */
class LoginController extends Controller {
    use AuthenticatesUsers;

    protected $provider;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->provider = new VatsimOAuthController;
    }

    public function login(Request $request) {
        if (!$request->has('code') || !$request->has('state')) { // User has clicked "login", redirect to Connect
            $authorizationUrl = $this->provider->getAuthorizationUrl(); // Generates state
            $request->session()->put('vatsimauthstate', $this->provider->getState());
            return redirect()->away($authorizationUrl);
        } elseif ($request->input('state') !== session()->pull('vatsimauthstate')) { // State mismatch, error
            return redirect('/')->withError("Something went wrong, please try again.");
        } else { // Callback (user has just logged in Connect)
            return $this->verifyLogin($request);
        }
    }

    protected function verifyLogin(Request $request) {
        try {
            $accessToken = $this->provider->getAccessToken('authorization_code', [
                'code' => $request->input('code')
            ]);
        } catch (IdentityProviderException $e) {
            return redirect('/')->withError("Something went wrong, please try again later.");
        }
        $resourceOwner = json_decode(json_encode($this->provider->getResourceOwner($accessToken)->toArray()));

        // Check if user has granted us the data we need
        if (
            !isset($resourceOwner->data) ||
            !isset($resourceOwner->data->cid) ||
            $resourceOwner->data->oauth->token_valid !== "true"
        ) {
            return redirect('/')->withError("We need you to grant us all marked permissions");
        }
        return $this->vatusaAuth($resourceOwner, $accessToken);
    }

    protected function vatusaAuth($resourceOwner, $accessToken) {
        $client = new Client();
        $result = $client->request('GET', 'https://api.vatusa.net/v2/user/' . $resourceOwner->data->cid . '?apikey=' . Config::get('vatusa.api_key'), ['http_errors' => false]);
        $realops_toggle_enabled = toggleEnabled('realops');

        if (! $result || $result->getStatusCode() != 200) {
            if ($realops_toggle_enabled) {
                return $this->externalRealopsLogin(
                    $resourceOwner->data->cid,
                    $resourceOwner->data->personal->name_first,
                    $resourceOwner->data->personal->name_last,
                    $resourceOwner->data->personal->email
                );
            }

            return redirect('/')->with('error', 'We are unable to verify your access at this time. Please try again in a few minutes.');
        }

        $resu = json_decode($result->getBody()->__toString(), true);

        if (! isset($resu['data'])) {
            return redirect('/')->with('error', 'We are unable to verify your access at this time. Please try again in a few minutes.');
        }

        $res = $resu['data'];
        $userstatuscheck = User::find($res['cid']);
        
        if (! $realops_toggle_enabled && ! $userstatuscheck) {
            return redirect('/')->with('error', 'You have not been found on the roster. If you have recently joined, please allow up to an hour for the roster to update.');
        } elseif (! $realops_toggle_enabled && $userstatuscheck->status == 2) {
            return redirect('/')->with('error', 'You have not been found on the roster. If you have recently joined, please allow up to an hour for the roster to update.');
        } elseif (! $userstatuscheck || $userstatuscheck->status == 2) {
            return $this->externalRealopsLogin($res['cid'], $res['fname'], $res['lname'], $res['email']);
        }

        $userstatuscheck->fname = $res['fname'];
        $userstatuscheck->lname = $res['lname'];
        $userstatuscheck->email = $res['email'];
        $userstatuscheck->rating_id = $res['rating'];

        if ($res['flag_broadcastOptedIn'] == 1 && $userstatuscheck->opt != 1) {
            $opt = new Opt;
            $opt->controller_id = $res['cid'];
            $opt->ip_address = '0.0.0.0';
            $opt->means = 'VATUSA API';
            $opt->option = 1;
            $opt->save();
            $userstatuscheck->opt = 1;
        } else {
            $user_opt = Opt::where('controller_id', $userstatuscheck->id)->where('means', '!=', 'VATUSA API')->where('option', 1)->first();
            if ($userstatuscheck->opt != 0 && !isset($user_opt)) {
                $opt = new Opt;
                $opt->controller_id = $res['cid'];
                $opt->ip_address = '0.0.0.0';
                $opt->means = 'VATUSA API';
                $opt->option = 0;
                $opt->save();
                $userstatuscheck->opt = 0;
            }
        }

        $userstatuscheck->visitor_from = null;

        if ($userstatuscheck->visitor == '1' && $res['facility'] != 'ZZN') {
            $userstatuscheck->visitor_from = $res['facility'];
        }

        $userstatuscheck->save();

        auth()->login($userstatuscheck, true);

        $message = 'You have been logged in successfully.';
        if ($userstatuscheck->status == 0) {
            $message = 'You have been logged in successfully. Please note that you are on an LOA and should not control until off the LOA. If this is an error, please let the DATM know.';
        }

        return redirect()->intended('/dashboard')->with('success', $message);
    }

    public function logout() {
        auth()->logout();
        auth()->guard('realops')->logout();

        return redirect('/')->withSuccess('You have been successfully logged out');
    }

    public function realopsLogin() {
        if (! auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();
        $realops_pilot = RealopsPilot::find($user->id);

        if (! $realops_pilot) {
            $realops_pilot = new RealopsPilot;
        }

        $realops_pilot->id = $user->id;
        $realops_pilot->fname = $user->fname;
        $realops_pilot->lname = $user->lname;
        $realops_pilot->email = $user->email;
        $realops_pilot->save();

        return $this->completeRealopsLogin($realops_pilot);
    }

    private function externalRealopsLogin($cid, $fname, $lname, $email) {
        $realops_pilot = RealopsPilot::find($cid);

        if (! $realops_pilot) {
            $realops_pilot = new RealopsPilot;
            $realops_pilot->id = $cid;
        }

        $realops_pilot->fname = $fname;
        $realops_pilot->lname = $lname;
        $realops_pilot->email = $email;
        $realops_pilot->save();

        return $this->completeRealopsLogin($realops_pilot);
    }

    private function completeRealopsLogin($realops_pilot) {
        auth()->guard('realops')->login($realops_pilot);
        return redirect('/realops');
    }
}
