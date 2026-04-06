<?php

return [
    'token' => env('DISCORD_API_TOKEN'),
    'guild_id' => env('DISCORD_GUILD_ID'),
    'event_role' => env('DISCORD_EVENT_ROLE', true), // True = enabled, False = disabled
    'event_role_name' => env('DISCORD_EVENT_ROLE_NAME', 'Event Participant'),
    'event_role_color' => env('DISCORD_EVENT_ROLE_COLOR', '#FFFFFF'), // 6-digit hex color
    'staffup_role_id' => env('DISCORD_STAFFUP_ROLE_ID', 1282498662957846539),
];
