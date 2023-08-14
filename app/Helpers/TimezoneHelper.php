<?php

use Carbon\Carbon;

function timeToLocal($time, $timezone) {
    $time_split = explode(':', $time);
    $time = Carbon::createFromTime(intval($time_split[0]), intval($time_split[1]), 0, "Etc/Zulu");

    $time->setTimezone($timezone);

    $local_time = sprintf("%02d", $time->hour).':'.sprintf("%02d", $time->minute);

    return $local_time;
}
