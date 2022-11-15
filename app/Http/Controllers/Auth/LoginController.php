<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use League\OAuth2\Client\Token;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VatsimOAuthController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Opt;
use Config;
use GuzzleHttp\Client;

/**
 * This controller handles authenticating users for the application and
 * redirecting them to your home screen. The controller uses a trait
 * to conveniently provide its functionality to your applications.
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $provider;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->provider = new VatsimOAuthController;
    }

    public function login(Request $request)
    {
        if (!$request->has('code') || !$request->has('state')) { // User has clicked "login", redirect to Connect
            $authorizationUrl = $this->provider->getAuthorizationUrl(); // Generates state
            $request->session()->put('vatsimauthstate', $this->provider->getState());
            return redirect()->away($authorizationUrl);
        } else if ($request->input('state') !== session()->pull('vatsimauthstate')) { // State mismatch, error
            return redirect('/')->withError("Something went wrong, please try again.");
        } else { // Callback (user has just logged in Connect)
            return $this->verifyLogin($request);
        }
    }

    protected function verifyLogin(Request $request)
    {
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

    protected function vatusaAuth($resourceOwner, $accessToken)
    {
        $client = new Client();
        $result = $client->request('GET', 'https://api.vatusa.net/v2/user/' . $resourceOwner->data->cid . '?apikey=' . Config::get('vatusa.api_key'));
        if ($result) {  // VATUSA API response
            $resu = json_decode($result->getBody()->__toString(), true);
            if (isset($resu['data'])) { // VATUSA API responded with JSON in expected format
                $res = $resu['data'];
                $userstatuscheck = User::find($res['cid']);
                if ($userstatuscheck) { // User's CID found on the facility roster
                    if ($userstatuscheck->status != 2) { // User is not marked inactive on the facility roster
                        $userstatuscheck->fname = $res['fname'];
                        $userstatuscheck->lname = $res['lname'];
                        $userstatuscheck->email = $res['email'];
                        $userstatuscheck->rating_id = $res['rating'];
                        if ($res['flag_broadcastOptedIn'] == 1) {
                            if ($userstatuscheck->opt != 1) {
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
                        if ($userstatuscheck->visitor == '1') {
                            if ($res['facility'] != 'ZZN') {
                                $userstatuscheck->visitor_from = $res['facility'];
                            }
                        } else {
                            $userstatuscheck->visitor_from = null;
                        }
                        $userstatuscheck->save();
                        $this->completeLogin($resourceOwner, $accessToken);
                        Auth::loginUsingId($res['cid'], true);

                    } else { // User was found on the roster, but is not an active home or visiting controller
                        return redirect('/')->with('error', 'You have not been found on the roster. If you have recently joined, please allow up to an hour for the roster to update.');
                    }
                } else { // User was not found on the facility roster as a home or visiting controller
                    return redirect('/')->with('error', 'You have not been found on the roster. If you have recently joined, please allow up to an hour for the roster to update.');
                }
            } else { // VATUSA API responded in an unexpected format (non-JSON)
                return redirect('/')->with('error', 'We are unable to verify your access at this time. Please try again in a few minutes.');
            }
            if ($userstatuscheck->status == 0) { // User is authenticated and has been logged in. LOA is no longer used at ZTL, this item retained for compatibility
                return redirect('/dashboard')->with('success', 'You have been logged in successfully. Please note that you are on an LOA and should not control until off the LOA. If this is an error, please let the DATM know.');
            } else { // User is authenticated and has been logged in
                return redirect()->intended('/dashboard')->with('success', 'You have been logged in successfully.');
            }
        } else { // VATUSA API does not respond or throws an error
            return redirect('/')->with('error', 'We are unable to verify your access at this time. Please try again in a few minutes.');
        }
    }

    protected function completeLogin($resourceOwner, $token)
    {
        $account = User::firstOrNew(['id' => $resourceOwner->data->cid]);
        if ($resourceOwner->data->oauth->token_valid === "true") { // User has given us permanent access to data
            $account->access_token = $token->getToken();
            $account->refresh_token = $token->getRefreshToken();
            $account->token_expires = $token->getExpires();
        }

        $account->save();
        auth()->login($account, true);

        return $account;
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/')->withSuccess('You have been successfully logged out');
    }
}
