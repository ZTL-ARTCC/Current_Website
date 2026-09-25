<?php

/*
    config/moodle.php
    Configure Moodle courses and lookup arrays

    Moodle Quiz Info
    ---------------------------------------------------------------------
    ID | Name                                                  | CMID
    ---------------------------------------------------------------------
    27 S1 DEL2: Altitude Rules Check for Understanding          194
    26 S1 DEL3: Check for Understanding                         190
    25 S2 Skill Check Readiness Quiz                            159
    29 S3 APP4 Skill Check Readiness Quiz                       221
    30 S3 APP5 Skill Check Readiness Quiz                       226
    24 C1 Skill Check Readiness Quiz                            138
    4  Atlanta ATCT SOP & LOA Exam                              45
    13 Satellite Radar                                          64
    14 Departure Radar                                          69
    15 Terminal Arrival Radar                                   74
    16 Arrival Radar                                            77
    -------------
    ID | Course
    -------------
    15  S1
    14  S2
    13  S3
    11  C1
    6   ATL
    7   A80
*/

return [
    // Moodle base URL
    'base_url' => env('MOODLE_BASE', 'https://learn.ztlartcc.org'),
    // Custom ARTCC Moodle name
    'lms_name' => env('MOODLE_NAME', 'Moodle'),

    // Map Moodle quiz IDs (key) to Moodle Course Module IDs / CMID (value)
    'cmid_lookup' => [27 => 194, 26 => 190, 25 => 159, 29 => 221, 30 => 226, 24 => 138, 4 => 45, 13 => 64, 14 => 69, 15 => 74, 16 => 77],

    // Quiz grade percentage considered passing
    'quiz_pass_pct' => 80,

    // Define which quizzes are related to a particular training program
    'S1' => [27,26], // OBS->S1
    'S2' => [15], // S1->S2
    'S3' => [29,30,4], // S2->S3
    'C1' => [4,13,14,15,16,24], // S3->C1
    'OTHER' => [4,13,14,15,16], // Default
];
