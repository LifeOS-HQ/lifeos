<?php

namespace App\Models\Work;

use App\Models\Work\Month;
use App\Models\Work\Time;
use App\Support\Holidays;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Year extends Model
{
    protected $appends = [
        'bonus_formatted',
        'gross_formatted',
        'net_formatted',
        'planned_working_hours_formatted',
        'planned_working_hours_day_formatted',
        'wage_bonus_formatted',
        'wage_formatted',
        'wage_total_formatted',
        'hours_worked_formatted',
        'hours_worked_day_formatted',
    ];

    protected $dates = [
        'date',
    ];

    protected $guarded = [
        'id'
    ];

    protected $table = 'working_years';

    /**
     * The booting method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->year = $model->date->year;

            $model->setAvailableWorkingDays();

            $model->planned_working_hours = 2300;

            $model->days_worked = 0;
            $model->hours_worked = 0;

            $model->wage_in_cents = 20 * 100;
            $model->wage_bonus_in_cents = 0;

            $model->bonus_in_cents = 0;
            $model->gross_in_cents = 0;
            $model->net_in_cents = 0;

            return true;
        });

        static::updating(function($model)
        {
            //
        });
    }


    public function cache() : self
    {
        $data = DB::table('working_months')
            ->select(DB::raw('SUM(hours_worked) AS hours_worked'), DB::raw('SUM(days_worked) AS days_worked'))
            ->where('year_id', $this->id)
            ->groupBy('year_id')
            ->first();

        $this->days_worked = $data->days_worked ?? 0;
        $this->hours_worked = $data->hours_worked ?? 0;
        $this->gross_in_cents = (($this->wage_in_cents + $this->wage_bonus_in_cents) * $this->hours_worked) + $this->bonus_in_cents;

        return $this;
    }

    public function cacheMonths() : self
    {
        foreach ($this->months as $month) {
            $month->cache()->save();
        }

        return $this;
    }

    public function setAvailableWorkingDays() : int
    {
        $holidayDates = Holidays::dates($this->year, Holidays::LAND_NW);
        $days = CarbonPeriod::create($this->date->startOfYear(), '1 day', $this->date->endOfYear());

        foreach ($days as $day) {
            if ($day->isWeekend()) {
                continue;
            }

            if ($holidayDates->contains($day->format('Y-m-d'))) {
                continue;
            }

            $this->available_working_days++;
        }

        return $this->available_working_days;
    }

    public function getPlannedWorkingHoursDayAttribute() : float
    {
        if ($this->attributes['planned_working_hours'] == 0 || $this->attributes['available_working_days'] == 0) {
            return 0;
        }

        return ($this->attributes['planned_working_hours'] / $this->attributes['available_working_days']);
    }

    public function getHoursWorkedDayFormattedAttribute() : string
    {
        return number_format($this->hours_worked_day, 2, ',', '.');
    }

    public function getHoursWorkedDayAttribute() : float
    {
        if ($this->attributes['hours_worked'] == 0 || $this->attributes['days_worked'] == 0) {
            return 0;
        }

        return ($this->attributes['hours_worked'] / $this->attributes['days_worked']);
    }

    public function getPlannedWorkingHoursDayFormattedAttribute() : string
    {
        return number_format($this->planned_working_hours_day, 2, ',', '.');
    }

    public function getPlannedWorkingHoursFormattedAttribute() : string
    {
        return number_format($this->planned_working_hours, 2, ',', '.');
    }

    public function getHoursWorkedFormattedAttribute() : string
    {
        return number_format($this->hours_worked, 2, ',', '.');
    }

    public function getWageFormattedAttribute() : string
    {
        return number_format(($this->attributes['wage_in_cents'] / 100), 2, ',', '.');
    }

    public function getWageTotalInCentsAttribute() : float
    {
        return ($this->wage_in_cents * $this->hours_worked);
    }

    public function getWageTotalFormattedAttribute() : string
    {
        return number_format(($this->wage_total_in_cents / 100), 2, ',', '.');
    }

    public function getWageBonusFormattedAttribute() : string
    {
        return number_format(($this->attributes['wage_bonus_in_cents'] / 100), 2, ',', '.');
    }

    public function getBonusFormattedAttribute() : string
    {
        return number_format(($this->attributes['bonus_in_cents'] / 100), 2, ',', '.');
    }

    public function getGrossFormattedAttribute() : string
    {
        return number_format(($this->attributes['gross_in_cents'] / 100), 2, ',', '.');
    }

    public function getNetFormattedAttribute() : string
    {
        return number_format(($this->attributes['net_in_cents'] / 100), 2, ',', '.');
    }

    public function months() : HasMany
    {
        return $this->hasMany(Month::class);
    }
}
