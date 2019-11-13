<?php

namespace App;

use App\User;
use DB;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;

class ControllerLog extends Model
{
    protected $table = 'controller_log';
    protected $fillable = ['id', 'cid', 'name', 'position', 'duration', 'date', 'time_logon', 'streamupdate', 'created_at', 'updated_at'];

    public function user()
	{
		return $this->hasOne('User', 'id', 'cid');
	}

	public function getDurationTimeAttribute()
	{
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

	public function getLocalHrsAttribute()
	{
		return floatval($this->attributes['local_hrs']) ? number_format($this->attributes['local_hrs'], 2) : '--';
	}

	public function getApproachHrsAttribute()
	{
		return floatval($this->attributes['approach_hrs']) ? number_format($this->attributes['approach_hrs'], 2) : '--';
	}

	public function getEnrouteHrsAttribute()
	{
		return floatval($this->attributes['enroute_hrs']) ? number_format($this->attributes['enroute_hrs'], 2) : '--';
	}

	public function getTotalHrsAttribute()
	{
		return floatval($this->attributes['total_hrs']) ? number_format($this->attributes['total_hrs'], 2) : '--';
	}

	public function getBronzeHrsAttribute()
	{
		return floatval($this->attributes['bronze_hrs']) ? number_format($this->attributes['bronze_hrs'], 2) : '--';
	}

	public static function getAllControllerStats()
	{
		return static::aggregateStatsByTime(static::query());
	}

	public static function getControllerStats($id)
	{
		return static::aggregateStatsByTime(static::where('cid', $id));
	}

	public static function aggregateStatsByTime(QueryBuilder $query)
	{
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

	public static function aggregateAllControllersByPosAndMonth( $year = null, $month = null )
	{
		$query = static::query()
			->rightJoin('roster', function($join) use ($year, $month) {
				$join->on('roster.id', '=', 'controller_log.cid');

				if ($month != null)
					$join->where(DB::Raw("date_format(STR_TO_DATE(`date`, '%m/%d/%Y'), '%c')"), 'like', $month);

				if ($year != null)
					$join->where(DB::Raw("date_format(STR_TO_DATE(`date`, '%m/%d/%Y'), '%y')"), 'like', $year);
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

		return $query->get()->reduce(function($m, $r){
			$m[$r->cid] = $r;
			return $m;
		}, []);
	}

	public static function aggregateAllControllersByPosAndYear( $year = null )
	{
		$query = static::query()
			->rightJoin('roster', function($join) use ($year) {
				$join->on('roster.id', '=', 'controller_log.cid');

				if ($year != null)
					$join->where(DB::Raw("date_format(STR_TO_DATE(`date`, '%m/%d/%Y'), '%y')"), 'like', $year);
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

		return $query->get()->reduce(function($m, $r){
			$m[$r->cid] = $r;
			return $m;
		}, []);
	}

}
