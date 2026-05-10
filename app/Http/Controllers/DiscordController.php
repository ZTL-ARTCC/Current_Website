<?php

namespace App\Http\Controllers;

use App\Enums\SessionVariables;
use App\Event;
use App\EventRegistration;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DiscordController extends Controller {

    private $guild_id = null;

    public function __construct() {
        $this->guild_id = config('discord.guild_id');
    }

    public function toggleEventRole($id) {
        $event = Event::find($id);
        if (!$event) {
            return back()->with(SessionVariables::ERROR->value, 'Unable to toggle event reference.');
        }
        if (!is_null($event->discord_role)) {
            $this->disableEventRole($id);
            return back()->with(SessionVariables::SUCCESS->value, "Event reference and roles removed in Discord.");
        }
        $this->enableEventRole($id);
        return back()->with(SessionVariables::SUCCESS->value, "Event reference set and role assigned in Discord.");
    }

    private function enableEventRole($id) {
        $event = Event::find($id);
        // Check to make sure role doesn't exist, if it does, delete it
        $role_id = $this->getRoleId(config('discord.event_role_name'));
        if (!is_null($role_id)) {
            $this->deleteRole($role_id);
        }
        Event::whereNotNull('discord_role')->update(['discord_role' => null]);
        // Create the role
        $event_role_id = $this->createRole(config('discord.event_role_name'), config('discord.event_role_color'));
        $event->discord_role = $event_role_id;
        $event->save();
        // Assign to controllers
        $event_controllers = EventRegistration::where('status', 1)->where('event_id', $event->id)->pluck('controller_id')->toArray();
        foreach ($event_controllers as $controller_id) {
            $controller = User::find($controller_id);
            if ($controller) {
                $this->assignUserRole($controller->discord, $event_role_id);
            }
        }
        //return redirect()->route('viewEvent', ['id' => $id])->with(SessionVariables::SUCCESS->value, "Event reference set and role assigned in Discord.");
        //return back(); //->with(SessionVariables::SUCCESS->value, "Event reference set and role assigned in Discord.");
    }

    private function disableEventRole($id) {
        $event = Event::find($id);
        $this->deleteRole($event->discord_role);
        $event->discord_role = null;
        $event->save();
        //return redirect()->route('viewEvent', ['id' => $id])->with(SessionVariables::SUCCESS->value, "Event reference and roles removed in Discord.");
        //return back(); //->with(SessionVariables::SUCCESS->value, "Event reference and roles removed in Discord.");
    }

    public function toggleStaffUpRole() {
        $user = Auth::user();
        $result = $this->toggleUserRole($user->discord, config('discord.staffup_role_id'));
        if (is_null($result)) {
            return back()->with(SessionVariables::ERROR->value, 'Unable to toggle roles at this time. Please try again later.');
        }
        return back()->with(SessionVariables::SUCCESS->value, "Your Discord StaffUp role has been $result.");
    }

    public function toggleUserRole($user_id, $role_id): string|null {
        if ($this->doesUserHaveRole($user_id, $role_id)) {
            $this->removeUserRole($user_id, $role_id);
            return 'removed';
        } else {
            $this->assignUserRole($user_id, $role_id);
            return 'added';
        }
        return null;
    }

    private function doesUserHaveRole($user_id, $role_id): bool {
        try {
            $client = new Client();
            $response = $client->request('GET', "https://discord.com/api/guilds/" . $this->guild_id . "/members/" . $user_id, [
                'headers' => [
                    'Authorization' => "Bot " . config('discord.token'),
                ]
            ]);

            $d_user_data = json_decode($response->getBody()->getContents(), true);
            $assigned_roles = $d_user_data['roles']; // This will be an array of role IDs

            if (in_array($role_id, $assigned_roles)) {
                return true;
            }
        } catch (RequestException $e) {
            Log::info('Unable to check Discord role assignment: ' . $e->getMessage());
            return false;
        }
        return false;
    }

    public function manageEventRole($event_id, $controller_id, $action): bool {
        if (!config('discord.event_role')) {
            return false;
        }
        $event = Event::find($event_id);
        if (is_null($event->discord_role)) {
            return false;
        }
        $controller = User::find($controller_id);
        if (!$event || !$controller) {
            return false;
        }
        if ($action == 'remove') {
            $this->removeUserRole($controller->discord, $event->discord_role);
        } else {
            $this->assignUserRole($controller->discord, $event->discord_role);
        }
        return true;
    }

    private function assignUserRole($user_id, $role_id) {
        $this->modifyUserRole($user_id, $role_id, 'assign');
    }

    private function removeUserRole($user_id, $role_id) {
        $this->modifyUserRole($user_id, $role_id, 'remove');
    }

    private function modifyUserRole($user_id, $role_id, $action) {
        $http_method = ($action == 'assign') ? 'PUT' : 'DELETE';
        try {
            $client = new Client();
            $response = $client->request($http_method, "https://discord.com/api/guilds/" . $this->guild_id . "/members/" . $user_id . "/roles/" . $role_id, [
                'headers' => [
                    'Authorization' => "Bot " . config('discord.token'),
                    'Content-Type' => 'application/json',
                ]
            ]);
        } catch (RequestException $e) {
            Log::info('Unable to modify Discord user role: ' . $e->getMessage());
        }
    }

    private function createRole($role_name, $role_color): int|null {
        try {
            $user = Auth::user();
            $client = new Client();
            $response = $client->request('POST', "https://discord.com/api/guilds/" . $this->guild_id . "/roles", [
                'headers' => [
                    'Authorization' => "Bot " . config('discord.token'),
                    'Content-Type' => "application/json"
                ],
                'json' => [
                    'name' => $role_name,
                    'permissions' => 0, // No permissions, this is a vanity role only
                    'color' => hexdec($role_color),
                    'hoist' => true,
                    'mentionable' => true,
                ],
            ]);
            $d_response_data = json_decode($response->getBody()->getContents(), true);
            return $d_response_data['id'] ?? null;

        } catch (RequestException $e) {
            Log::info('Unable to create Discord event role: ' . $e->getMessage());
        }
    }

    private function deleteRole($role_id) {
        try {
            $client = new Client();
            $response = $client->request('DELETE', "https://discord.com/api/guilds/" . $this->guild_id . "/roles/" . $role_id, [
                'headers' => [
                    'Authorization' => "Bot " . config('discord.token')
                ]
            ]);
        } catch (RequestException $e) {
            Log::info('Unable to delete Discord event role: ' . $e->getMessage());
        }
    }

    private function getRoleId($role_name): int|null {
        try {
            $client = new Client();
            $response = $client->request('GET', "https://discord.com/api/guilds/" . $this->guild_id . "/roles", [
                'headers' => [
                    'Authorization' => "Bot " . config('discord.token'),
                ]
            ]);

            $d_role_data = json_decode($response->getBody()->getContents(), true);
            foreach ($d_role_data as $role) {
                if ($role['name'] == $role_name) {
                    return $role['id'];
                }
            }
            return null;

        } catch (RequestException $e) {
            Log::info('Unable to fetch Discord event role ID: ' . $e->getMessage());
        }
    }
}
