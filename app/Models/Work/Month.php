<?php

namespace App\Models\Work;

use App\Models\Work\Time;
use App\Models\Work\Year;
use App\Support\Holidays;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Month extends Model
{
    protected $appends = [
        'bonus_formatted',
        'date_formatted',
        'gross_formatted',
        'holiday_hours_worked',
        'hours_worked_day',
        'hours_worked_day_formatted',
        'hours_worked_formatted',
        'is_current_month',
        'net_formatted',
        'path',
        'planned_working_hours',
        'planned_working_hours_day',
        'planned_working_hours_day_formatted',
        'planned_working_hours_formatted',
        'wage_bonus_formatted',
        'wage_bonus_in_cents',
        'wage_formatted',
        'wage_in_cents',
    ];

    protected $casts = [
        'hours_worked' => 'decimal:2',
    ];

    protected $dates = [
        'date',
    ];

    protected $fillable = [
        'available_working_days',
        'bonus_formatted',
        'bonus_in_cents',
        'date',
        'days_worked',
        'gross_in_cents',
        'hours_worked',
        'month',
        'net_formatted',
        'net_in_cents',
        'user_id',
        'workingdays_hours_worked',
        'workingdays_worked',
        'year_id',
    ];

    protected $table = 'working_months';

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
            $model->month = $model->date->month;
            $model->setAvailableWorkingDays();

            $model->days_worked = 0;
            $model->workingdays_worked = 0;
            $model->workingdays_hours_worked = 0;
            $model->hours_worked = 0;

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

    public function setAvailableWorkingDays() : int
    {
        $holidayDates = Holidays::dates($this->date->year, Holidays::LAND_NW);
        $days = CarbonPeriod::create($this->date->startOfMonth(), '1 day', $this->date->endOfMonth());

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

    public function cache() : self
    {
        $data = DB::table('working_times')
            ->select(DB::raw('SUM(seconds) AS seconds'), DB::raw('SUM(seconds_break) AS seconds_break'), DB::raw('COUNT(DISTINCT DATE(start_at)) AS days_worked'))
            ->where('month_id', $this->id)
            ->whereNotNull('end_at')
            ->groupBy('month_id')
            ->first();

        $this->days_worked = $data->days_worked ?? 0;
        $this->hours_worked = Time::toIndustryHours((is_null($data->seconds) ? 0 : ($data->seconds - $data->seconds_break)));
        $this->gross_in_cents = $this->wage_in_cents + $this->wage_bonus_in_cents + $this->bonus_in_cents;

        $data = DB::table('working_times')
            ->select(DB::raw('SUM(seconds) AS seconds'), DB::raw('SUM(seconds_break) AS seconds_break'), DB::raw('COUNT(DISTINCT DATE(start_at)) AS workingdays_worked'))
            ->where('month_id', $this->id)
            ->where('is_workingday', true)
            ->whereNotNull('end_at')
            ->groupBy('month_id')
            ->first();

        $this->workingdays_worked = $data->workingdays_worked ?? 0;
        $this->workingdays_hours_worked = Time::toIndustryHours((is_null($data->seconds) ? 0 : ($data->seconds - $data->seconds_break)));

        return $this;
    }

    public function getBonusFormattedAttribute() : string
    {
        return number_format(($this->attributes['bonus_in_cents'] / 100), 2, ',', '');
    }

    public function setBonusFormattedAttribute(string $value) : void
    {
        $this->bonus_in_cents = str_replace(',', '.', $value) * 100;
        Arr::forget($this->attributes, 'bonus_formatted');
    }

    public function getDateFormattedAttribute() : string
    {
        return $this->date->monthName . ' ' . $this->date->year;
    }

    public function getHoursWorkedDayFormattedAttribute() : string
    {
        return number_format($this->hours_worked_day, 2, ',', '.');
    }

    public function getHoursWorkedDayAttribute() : float
    {
        if ($this->attributes['workingdays_hours_worked'] == 0 || $this->attributes['workingdays_worked'] == 0) {
            return 0;
        }

        return ($this->attributes['workingdays_hours_worked'] / $this->attributes['workingdays_worked']);
    }

    public function getHolidayHoursWorkedAttribute() : float
    {
        return ($this->attributes['hours_worked'] - $this->attributes['workingdays_hours_worked']);
    }

    public function getIsCurrentMonthAttribute() : bool
    {
        return ($this->date->month == today()->month);
    }

    public function getNetFormattedAttribute() : string
    {
        return number_format(($this->attributes['net_in_cents'] / 100), 2, ',', '');
    }

    public function setNetFormattedAttribute(string $value) : void
    {
        $this->net_in_cents = str_replace(',', '.', $value) * 100;
        Arr::forget($this->attributes, 'net_formatted');
    }

    public function getPathAttribute()
    {
        return '/work/month/' . $this->id;
    }

    public function getPlannedWorkingHoursDayAttribute() : float
    {
        return $this->year->planned_working_hours_day;
    }

    public function getPlannedWorkingHoursDayFormattedAttribute() : string
    {
        return number_format($this->planned_working_hours_day, 2, ',', '.');
    }

    public function getPlannedWorkingHoursAttribute() : string
    {
        return ($this->planned_working_hours_day * $this->available_working_days);
    }

    public function getPlannedWorkingHoursFormattedAttribute() : string
    {
        return number_format($this->planned_working_hours, 2, ',', '.');
    }

    public function getHoursWorkedFormattedAttribute() : string
    {
        return number_format($this->hours_worked, 2, ',', '.');
    }

    public function getGrossFormattedAttribute() : string
    {
        return number_format(($this->attributes['gross_in_cents'] / 100), 2, ',', '.');
    }

    public function getWageInCentsAttribute() : string
    {
        return ($this->year->wage_in_cents * $this->attributes['hours_worked']);
    }

    public function getWageFormattedAttribute() : string
    {
        return number_format(($this->wage_in_cents / 100), 2, ',', '.');
    }

    public function getWageBonusInCentsAttribute() : string
    {
        return ($this->year->wage_bonus_in_cents * $this->attributes['hours_worked']);
    }

    public function getWageBonusFormattedAttribute() : string
    {
        return number_format(($this->wage_bonus_in_cents / 100), 2, ',', '.');
    }

    public function year() : BelongsTo
    {
        return $this->belongsTo(Year::class, 'year_id');
    }

    public function times() : HasMany
    {
        return $this->hasMany(Time::class);
    }

    public function scopeYear(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where(DB::raw('YEAR(date)'), $value);
    }
}
