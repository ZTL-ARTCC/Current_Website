<?php

use App\FeatureToggle;

function toggleEnabled($toggle_name) {
    return FeatureToggle::isEnabled($toggle_name);
}
