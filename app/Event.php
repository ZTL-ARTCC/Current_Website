<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model {
    protected $table = 'events';
    protected $fillable = ['id', 'name', 'description', 'date', 'start_time', 'end_time', 'banner_path', 'banner_path_reduced', 'status', 'id_topic', 'created_at', 'updated_at'];
    protected $banner_base_path = 'event_banners/';
    protected $banner_reduced_base_path = 'reduced/';

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

    public function displayBannerPath() {
        if (starts_with($this->banner_path, "http://") || starts_with($this->banner_path, "https://")) {
            return $this->banner_path;
        }
        $disk = Storage::disk('public');
        $filename = basename($this->banner_path);
        if ($disk->exists($this->banner_base_path . $this->banner_reduced_base_path . $filename)) {
            return $disk->url($this->banner_base_path . $this->banner_reduced_base_path . $filename);
        } elseif ($disk->exists($this->banner_base_path . $filename)) {
            return $disk->url($this->banner_base_path . $filename);
        }
        return null;
    }

    public function reduceEventBanner() {
        $disk = Storage::disk('public');
        $filename = basename($this->banner_path);
        if (!$disk->exists($this->banner_base_path . $this->banner_reduced_base_path . $filename)) {
            if ($disk->exists($this->banner_base_path . $filename)) {
                $path = $disk->path($this->banner_base_path . basename($this->banner_path));
                $directory = dirname($disk->path($this->banner_base_path . $filename));
                list($width, $height) = getimagesize($path);
                $new_width = 1500; // No reason for these banners to be > 1500 px wide
                $new_height = ($new_width / $width) * $height;
                $im = new \IMagick();
                $im->readImage($path);
                $im->resizeImage($new_width, $new_height, \Imagick::FILTER_LANCZOS, 0.9, true);
                $im->writeImage($directory . '/' . $this->banner_reduced_base_path . $filename);
                $im->destroy();
            }
        }
        if ($disk->exists($this->banner_base_path . $this->banner_reduced_base_path . $filename)) {
            $this->banner_reduced_path = $disk->url($this->banner_base_path . $this->banner_reduced_base_path . $filename);
        }
    }

    public function toggleRegistration(): void {
        $this->reg = ($this->reg + 1) % 2;
        $this->save();

        Audit::newAudit('toggled event registration for ' . $this->name);
    }

    public function toggleShowAssignments(): void {
        $this->show_assignments = ! $this->show_assignments;
        $this->save();

        Audit::newAudit('toggled event assignment visibility for ' . $this->name);
    }
}
