<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;

class ControllerLog extends Model {
    protected $table = 'controller_log';
    protected $fillable = ['id', 'cid', 'name', 'position', 'duration', 'date', 'time_logon', 'streamupdate', 'created_at', 'updated_at'];

    public function user() {
        return $this->hasOne('User', 'id', 'cid');
    }

    public function getDurationTimeAttribute() {
        $seconds_count = $this->duration;
        $delimiter  = ':';
        $seconds = $seconds_count % 60;
        $minutes = floor($seconds_count/60)%60;
        $hours   = floor($seconds_count/3600);

        $seconds = str_pad($seconds, 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
        $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);

        return "$hours$delimiter$minutes$delimiter$seconds";
    }

    public function getLocalHrsAttribute() {
        return floatval($this->attributes['local_hrs']) ? number_format($this->attributes['local_hrs'], 2) : '--';
    }

    public function getApproachHrsAttribute() {
        return floatval($this->attributes['approach_hrs']) ? number_format($this->attributes['approach_hrs'], 2) : '--';
    }

    public function getEnrouteHrsAttribute() {
        return floatval($this->attributes['enroute_hrs']) ? number_format($this->attributes['enroute_hrs'], 2) : '--';
    }

    public function getTotalHrsAttribute() {
        return floatval($this->attributes['total_hrs']) ? number_format($this->attributes['total_hrs'], 2) : '--';
    }

    public function getBronzeHrsAttribute() {
        return floatval($this->attributes['bronze_hrs']) ? number_format($this->attributes['bronze_hrs'], 2) : '--';
    }

    public function getLocalHeroHrsAttribute() {
        return floatval($this->attributes['local_hero_hrs']) ? number_format($this->attributes['local_hero_hrs'], 2) : '--';
    }

    public static function getAllControllerStats() {
        return static::aggregateStatsByTime(static::query());
    }

    public static function getControllerStats($id) {
        return static::aggregateStatsByTime(static::where('cid', $id));
    }

    public static function aggregateStatsByTime(QueryBuilder $query) {
        $results = $query->select(DB::raw("
			SUM(IF(date_format(STR_TO_DATE(`date`, '%m/%d/%Y'), '%m/%Y') = date_format(now(), '%m/%Y'), duration, 0)) as `month`,
			SUM(IF(year(STR_TO_DATE(`date`, '%m/%d/%Y')) = year(now()), duration, 0)) as `year`,
			SUM(duration) as `total`
		"))->first();

        // Convert seconds to hours
        return [
            'month' => $results->month / 3600,
            'year' => $results->year / 3600,
            'total' => $results->total / 3600,
        ];
    }

    public static function aggregateAllControllersByPosAndMonth($year = null, $month = null) {
        $local_hero_query = static::localHeroQueryBuilder($year, $month);
        $query = static::query()
            ->rightJoin('roster', function ($join) use ($year, $month) {
                $join->on('roster.id', '=', 'controller_log.cid');

                if ($month != null) {
                    $join->where(DB::Raw("date_format(STR_TO_DATE(`date`, '%m/%d/%Y'), '%c')"), 'like', $month);
                }

                if ($year != null) {
                    $join->where(DB::Raw("date_format(STR_TO_DATE(`date`, '%m/%d/%Y'), '%y')"), 'like', $year);
                }
            })
            ->selectRaw("
				roster.id `cid`,
				SUM(IF(position LIKE '%_DEL' OR position LIKE '%_GND' OR position LIKE '%_TWR', duration, 0)) / 3600 `local_hrs`,
				SUM(IF(position LIKE '%_APP' OR position LIKE '%_DEP', duration, 0)) / 3600 `approach_hrs`,
				SUM(IF(position LIKE '%_CTR', duration, 0)) / 3600 `enroute_hrs`,
				SUM(IF(position LIKE '%_CTR' OR position LIKE '%_APP' OR position LIKE '%_DEP' OR position LIKE '%_TWR', duration, 0)) / 3600 `bronze_hrs`,
                " . $local_hero_query['query'] . "
				SUM(duration) / 3600 `total_hrs`
			", $local_hero_query['bindings'])
            ->groupBy('roster.id');

        return $query->get()->reduce(function ($m, $r) {
            $m[$r->cid] = $r;
            return $m;
        }, []);
    }

    public static function aggregateAllControllersByPosAndYear($year = null) {
        $query = static::query()
            ->rightJoin('roster', function ($join) use ($year) {
                $join->on('roster.id', '=', 'controller_log.cid');

                if ($year != null) {
                    $join->where(DB::Raw("date_format(STR_TO_DATE(`date`, '%m/%d/%Y'), '%y')"), 'like', $year);
                }
            })
            ->selectRaw("
				roster.id `cid`,
				SUM(IF(position LIKE '%_DEL' OR position LIKE '%_GND' OR position LIKE '%_TWR', duration, 0)) / 3600 `local_hrs`,
				SUM(IF(position LIKE '%_APP' OR position LIKE '%_DEP', duration, 0)) / 3600 `approach_hrs`,
				SUM(IF(position LIKE '%_CTR', duration, 0)) / 3600 `enroute_hrs`,
				SUM(IF(position LIKE '%_CTR' OR position LIKE '%_APP' OR position LIKE '%_DEP' OR position LIKE '%_TWR', duration, 0)) / 3600 `bronze_hrs`,
				SUM(duration) / 3600 `total_hrs`
			")
            ->groupBy('roster.id');

        return $query->get()->reduce(function ($m, $r) {
            $m[$r->cid] = $r;
            return $m;
        }, []);
    }

    private static function localHeroQueryBuilder($year, $month) { // If there is a configured local hero challenge, the build the query. If not, the use the default challenge.
        $bindings = [];
        $query = "SUM(IF(";
        $default_query = "SUM(IF(position LIKE 'BHM_%' OR position LIKE 'GSP_%' OR position LIKE 'AVL_%' OR position LIKE 'GSO_%' OR position LIKE 'TYS_%' OR position LIKE 'CHA_%' OR position LIKE 'FTY_%' 
        OR position LIKE 'RYY_%' OR position LIKE 'AHN_%' OR position LIKE 'AGS_%' OR position LIKE 'GMU_%' OR position LIKE 'GYH_%' OR position LIKE 'TCL_%' OR position LIKE 'MXF_%'
        OR position LIKE 'MGM_%' OR position LIKE 'LSF_%' OR position LIKE 'CSG_%' OR position LIKE 'MCN_%' OR position LIKE 'WRB_%' OR position LIKE 'JQF_%' OR position LIKE 'VUJ_%'
        OR position LIKE 'INT_%' OR position LIKE 'TRI_%' OR position LIKE 'LZU_%' OR position LIKE 'ASN_%' OR position LIKE 'HKY_%' OR position LIKE 'PDK_%', duration, 0)) / 3600 `local_hero_hrs`,";
        $local_hero_challenge = LocalHeroChallenges::where('year', $year)->where('month', $month)->first();
        if ($local_hero_challenge) {
            if (strlen($local_hero_challenge->positions > 0)) {
                $positions = str_getcsv($local_hero_challenge->positions);
                foreach ($positions as $p=>$position) {
                    if ($p > 0) {
                        $query .= " OR ";
                    }
                    $pos = explode('_', $position);
                    $query .= "position LIKE ?";
                    $bindings[] = trim($pos[0]) . '_%' . trim($pos[1]);
                }
                $query .= ", duration, 0)) / 3600 `local_hero_hrs`,";
                return ['query'=>$query, 'bindings'=>$bindings];
            }
        }

        return ['query'=>$default_query, 'bindings'=>[]];
    }
}
