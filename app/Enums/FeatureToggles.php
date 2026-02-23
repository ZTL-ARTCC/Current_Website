<?php

namespace App\Enums;

enum FeatureToggles: string {
    case REALOPS = 'realops';
    case REALOPS_BIDDING = 'realops_bidding';
    case MOODLE = 'moodle';
    case MERCH_STORE = 'merch-store';
    case CUSTOM_THEME_LOGO = 'custom_theme_logo';
    case LOCAL_HERO = 'local-hero';
    case AUTO_SUPPORT_EVENTS = 'auto_support_events';
}
