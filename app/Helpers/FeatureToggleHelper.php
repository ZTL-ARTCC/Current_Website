<?php

use App\FeatureToggle;

function toggleEnabled($toggle_enum) {
    return FeatureToggle::isEnabled($toggle_enum);
}
