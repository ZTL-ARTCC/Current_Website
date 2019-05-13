<?php

namespace App\Http\Controllers;

use App\DiscordUser;
use App\User;
use Auth;
use Config;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class DiscordController extends Controller
{
    public function returnDiscordInfo(Request $request) {
        if($request->key == Config::get('discord.bot_api_key')) {
            $data = DiscordUser::get();
            return json_encode($data);
        } else {
            $data = ['error' => 'Wrong API Key'];
            return json_encode($data);
        }
    }

    public function loginToDiscord() {
        $discordUser = DiscordUser::fine(Auth::id());
        if($discordUser)
            return redirect()->back()->with('error', 'Your account is already linked.');

        return redirect('https://discordapp.com/api/oauth2/authorize' . '?response_type=code&scope=identify&client_id=' . Config::get('discord.client_id'));
    }

    public function completeDiscordLogin(Request $request)
    {
        $code = $request->code;
        if (!$code)
            return redirect('/')->with('error', 'No response code found for discord login.');

        $client = new Client();
        $response = $client->request('POST', 'https://discordapp.com/api/oauth2/token', [
            'form_params' => [
                'client_id' => Config::get('discord.client_id'),
                'client_secret' => Config::get('discord.secret'),
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => 'http://localhost:8000/discord/login/verify',
                'scope' => 'identify'
            ]
        ]);

        $res = json_decode($response->getBody());
        $token = $res->access_token;
        $response = $client->request('GET', 'https://discordapp.com/api/users/@me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);

        $res = json_decode($response->getBody());

        // Add the user to the database
        $user = User::find(Auth::id());
        $discordUser = new DiscordUser();
        $discordUser->cid = $user->id;
        $discordUser->name = $user->full_name;
        $discordUser->rating_id = $user->rating_id;
        $discordUser->discord_id = $res->id;
        $discordUser->discord_username = $res->username . "#" . $res->discriminator;
        $discordUser->save();

        return redirect('/dashboard/controllers/profile')->with('success', 'Discord profile linked successfully.');
    }
}
