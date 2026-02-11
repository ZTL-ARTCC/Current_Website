<?php

namespace App;

use App\Enums\FeatureToggles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FeatureToggle extends Model {
    protected $table = 'feature_toggles';
    protected $primaryKey = 'toggle_name';
    protected $keyType = 'string';
    public $incrementing = false;

    public static function isEnabled($toggle_enum) {
        return FeatureToggle::getToggleValue($toggle_enum);
    }
     
    public static function toggle($toggle_name) {
        $toggle = FeatureToggle::find($toggle_name);
        $toggle_enum = FeatureToggles::from($toggle_name);
        $toggle_value = FeatureToggle::getToggleValue($toggle_enum);

        if ($toggle) {
            $toggle->is_enabled = ! $toggle_value;
            $toggle->save();
        }

        Cache::put(FeatureToggle::generateToggleCacheName($toggle_enum), $toggle->is_enabled);
    }

    private static function generateToggleCacheName($toggle_enum) {
        return 'FeatureToggle_' . $toggle_enum->value;
    }

    private static function getToggleValue($toggle_enum) {
        return Cache::rememberForever(FeatureToggle::generateToggleCacheName($toggle_enum), function () use ($toggle_enum) {
            $toggle = FeatureToggle::find($toggle_enum->value);
            return $toggle != null && $toggle->is_enabled;
        });
    }

    public static function updateToggle($toggle_name_orig, $toggle_name, $toggle_description) {
        $toggle = FeatureToggle::find($toggle_name_orig);
        $toggle->toggle_name = $toggle_name;
        $toggle->toggle_description = $toggle_description;
        $toggle->save();
    }

    public static function deleteToggle($toggle_name) {
        $toggle = FeatureToggle::find($toggle_name);
        if ($toggle) {
            $toggle->delete();
            return true;
        }
        return false;
    }
}
