<?php

use Carbon\Carbon;

function convertTime($time, $from, $to): string {
    try {
        $time_split = explode(':', $time);
        $time = Carbon::createFromTime(intval($time_split[0]), intval($time_split[1]), 0, $from);

        $time->setTimezone($to);

        $converted = sprintf("%02d", $time->hour).':'.sprintf("%02d", $time->minute);

        return $converted;
    } catch (Exception $e) {
        return $time." [invalid]";
    }
}

function timeToLocal($time, $timezone): string {
    return convertTime($time, 'Etc/Zulu', $timezone);
}

function timeFromLocal($time, $timezone): string {
    return convertTime($time, $timezone, 'Etc/Zulu');
}
