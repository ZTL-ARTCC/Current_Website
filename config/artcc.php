<?php

/*
    config/artcc.php
    Set these configuration values in your .env file to update ARTCC-specific
    variables such as email addresses and training standards.
*/
return [

    // Facility 3-letter ID
    'id' => env('ARTCC_ID', 'ZTL'),
    // Facility short name
    'short_name' => env('ARTCC_SHORT_NAME', 'vZTL'),
    'long_name' => env('ARTCC_LONG_NAME', 'Atlanta ARTCC'),

    // Set staff email addresses (used in staff view and email templates)
    'email_atm' => env('ARTCC_EMAIL_ATM', 'atm@ztlartcc.org'),
    'email_datm' => env('ARTCC_EMAIL_DATM', 'datm@ztlartcc.org'),
    'email_ta' => env('ARTCC_EMAIL_TA', 'ta@ztlartcc.org'),
    'email_ec' => env('ARTCC_EMAIL_EC', 'ec@ztlartcc.org'),
    'email_fe' => env('ARTCC_EMAIL_FE', 'fe@ztlartcc.org'),
    'email_wm' => env('ARTCC_EMAIL_WM', 'wm@ztlartcc.org'),
    'email_test' =>env('ARTCC_EMAIL_TEST', 'test@ztlartcc.org'),

    // Training tickets are CC'd to this address
    'email_training' => env('ARTCC_EMAIL_TRAINING', 'training@ztlartcc.org'),

    // Placeholder email address for forms
    'email_placeholder' => env('ARTCC_EMAIL_PLACEHOLDER', 'example@ztlartcc.org'),

    // Merch store email
    'email_merch_store' => env('ARTCC_MERCH_STORE_EMAIL', 'datm@ztlartcc.org'),

    // Number of sessions training staff are required to provide per month
    'trainer_min_sessions' => env('ARTCC_TRAINER_MONTHLY_SESSIONS', 3),

    // Solo cert facilities (3-letter ID)
    'solo_twr' => env('ARTCC_SOLO_TWR', 'BHM'),
    'solo_app' => env('ARTCC_SOLO_APP', 'BHM'),
    'solo_ctr' => env('ARTCC_SOLO_CTR', 'ZTL'),

    // Teamspeak (adds teamspeak info views and links)
    'teamspeak' => env('ARTCC_TS', true),
    'teamspeak_server' => env('ARTCC_TS_SERVER', 'ts.ztlartcc.org'),
    'teamspeak_port' => env('ARTCC_TS_PORT', '9987'),

    // IDS Link (adds a link for IDS to the sidebar nav menu)
    'ids' => env('ARTCC_IDS', true),
    'ids_name' => env('ARTCC_IDS_NAME', 'vIDS'),
    'ids_link' => env('ARTCC_IDS_LINK', 'https://ids.ztlartcc.org'),

];
