<?php

function groupAtcBookingsByDate($bookings) {
    $bookings_grouped = [];

    foreach ($bookings as $b) {
        $date = $b->start_date_formatted;

        if (! array_key_exists($date, $bookings_grouped)) {
            $bookings_grouped[$date] = [];
        }

        array_push($bookings_grouped[$date], $b);
    }

    return $bookings_grouped;
}
