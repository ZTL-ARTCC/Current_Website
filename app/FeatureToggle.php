<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FeatureToggle extends Model {
    protected $table = 'feature_toggles';
    protected $primaryKey = 'toggle_name';
    protected $keyType = 'string';
    public $incrementing = false;

    public static function isEnabled($toggle_name) {
        return FeatureToggle::getToggleValue($toggle_name);
    }
     
    public static function toggle($toggle_name) {
        $toggle = FeatureToggle::find($toggle_name);
        $toggle_value = FeatureToggle::getToggleValue($toggle_name);

        if ($toggle) {
            $toggle->is_enabled = ! $toggle_value;
            $toggle->save();
        }

        Cache::put(FeatureToggle::generateToggleCacheName($toggle_name), $toggle->is_enabled);
    }

    private static function generateToggleCacheName($toggle_name) {
        return 'FeatureToggle_' . $toggle_name;
    }

    private static function getToggleValue($toggle_name) {
        return Cache::rememberForever(FeatureToggle::generateToggleCacheName($toggle_name), function () use ($toggle_name) {
            $toggle = FeatureToggle::find($toggle_name);
            return $toggle != null && $toggle->is_enabled;
        });
    }
}
