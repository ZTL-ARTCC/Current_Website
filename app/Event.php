<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model {
    protected $table = 'events';
    protected $fillable = ['id', 'name', 'description', 'date', 'start_time', 'end_time', 'banner_path', 'status', 'created_at', 'updated_at'];

    public function getDateEditAttribute() {
        $date = new Carbon($this->date);
        $date = $date->format('Y-m-d');
        return $date;
    }

    public static function fetchVisibleEvents() {
        $now = Carbon::now();
        $events = Event::where('status', 1)->get()->filter(function ($e) use ($now) {
            return strtotime($e->date.' '.$e->start_time) > strtotime($now);
        })->sortBy(function ($e) {
            return strtotime($e->date);
        });
        return $events;
    }

    public static function reduceEventBanner(&$e) {
        $disk = Storage::disk('public');
        $filename = basename($e->banner_path);
        $banner_path = 'event_banners/';
        $reduced_path = $banner_path . 'reduced/';
        if (!$disk->exists($reduced_path . $filename)) {
            if ($disk->exists($banner_path . $filename)) {
                $path = $disk->path($banner_path . basename($e->banner_path));
                $directory = dirname($disk->path($banner_path . $filename));
                list($width, $height) = getimagesize($path);
                $new_width = 1500; // No reason for these banners to be > 1500 px wide
                $new_height = ($new_width / $width) * $height;
                $im = new \IMagick();
                $im->readImage($path);
                $im->resizeImage($new_width, $new_height, \Imagick::FILTER_LANCZOS, 0.9, true);
                $im->writeImage($directory . '/reduced/' . $filename);
                $im->destroy();
            }
        }
        if ($disk->exists($reduced_path . $filename)) {
            $directory = dirname($disk->path($banner_path . $filename));
            $e->banner_path = $disk->url($reduced_path . $filename);
        }
    }
}
