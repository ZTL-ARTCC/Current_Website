<?php

function monitoredAirfields() {
    return [
        //"AGS" => "Augusta",
        "ATL" => "Atlanta",
        //"AVL" => "Asheville",
        //"BHM" => "Birmingham",
        //"CHA" => "Chattanooga",
        "CLT" => "Charlotte",
        //"GSP" => "Greensboro",
        //"MGM" => "Montgomery",
        //"PDK" => "Peachtree",
        //"TRI" => "Tri-Cities",
        //"TYS" => "Tyson"
    ];
}

function isMonitoredAirfield($airfield) {
    return key_exists($airfield, monitoredAirfields());
}
