<?php

function monitoredAirfields() {
    return [
        // Format:
        // "FAA" => "HumanName",
        // FAA is the 3-letter FAA identifier of the airport
        // Airports listed here will be picked up by the airfield monitoring
        // and added to the Airspace Summary on the homepage, in the order
        // they are specified here.
        "ATL" => "Atlanta",
        "CLT" => "Charlotte",
    ];
}

function isMonitoredAirfield($airfield) {
    return key_exists($airfield, monitoredAirfields());
}
