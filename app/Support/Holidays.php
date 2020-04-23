<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Holidays
{
    const LAND_NW = 'NW';

    public static function get(int $jahr, string $nur_land = null) : array
    {
        $key = self::key($jahr, $nur_land);
        if (Cache::has($key)) {
            return Cache::get($key);
        }

        return Cache::rememberForever($key, function () use ($jahr, $nur_land) {
            return self::fetch($jahr, $nur_land);
        });
    }

    public static function dates(int $jahr, string $nur_land = null) : Collection
    {
        $key = self::key($jahr, $nur_land);
        if (Cache::has($key)) {
            return Cache::get($key);
        }

        return Cache::rememberForever($key, function () use ($jahr, $nur_land) {
            $holidayDates = [];
            foreach (self::get($jahr, $nur_land) as $holiday) {
                $holidayDates[] = $holiday['datum'];
            }

            return collect($holidayDates);
        });
    }

    public static function key(int $jahr, string $nur_land = null) : string
    {
        return 'holidays.' . $jahr . ($nur_land ? '.' . $nur_land : '');
    }

    public static function fetch(int $jahr, string $nur_land = null) : array
    {
        return json_decode(file_get_contents('https://feiertage-api.de/api/?jahr=' . $jahr . ($nur_land ? '&nur_land=' . $nur_land : '')), true);
    }

    public static function isWorkingday(Carbon $day) : bool
    {
        if ($day->isWeekend()) {
            return false;
        }

        if (Holidays::dates($day->year, Holidays::LAND_NW)->contains($day->format('Y-m-d'))) {
            return false;
        }

        return true;
    }
}