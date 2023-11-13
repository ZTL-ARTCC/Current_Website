<?php

return [
    'api_key' => env('FLIGHTAWARE_API_KEY'),
    'base' => env('FLIGHTAWARE_BASE', 'https://aeroapi.flightaware.com/aeroapi'),

    'start_date' => env('FLIGHTAWARE_START_DATE'),
    'end_date' => env('FLIGHTAWARE_END_DATE'),

    'max_flights' => env('FLIGHTAWARE_MAX_FLIGHTS', 5000),
    'flights_per_page' => env('FLIGHTAWARE_FLIGHTS_PER_PAGE', 15),
    'departure_pages_per_chunk' => env('FLIGHTAWARE_DEPARTURE_PAGES_PER_CHUNK', 1),
    'arrival_pages_per_chunk' => env('FLIGHTAWARE_ARRIVAL_PAGES_PER_CHUNK', 1)
];
