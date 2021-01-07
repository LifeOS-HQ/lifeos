<?php

namespace App\Models\Work;

use App\Models\Work\Month;
use App\Models\Work\Time;
use App\Support\Holidays;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Year extends Model
{
    protected $appends = [
        'bonus_formatted',
        'bonus_months_formatted',
        'gross_formatted',
        'hours_worked_day',
        'hours_worked_day_formatted',
        'hours_worked_formatted',
        'net_formatted',
        'path',
        'planned_working_hours_day',
        'planned_working_hours_day_formatted',
        'planned_working_hours_formatted',
        'tax_refund_formatted',
        'wage_bonus_formatted',
        'wage_formatted',
        'wage_total_formatted',
    ];

    protected $dates = [
        'date',
    ];

    protected $fillable = [
        'user_id',
        'date',
        'year',
        'available_working_days',
        'planned_working_hours',
        'days_worked',
        'workingdays_worked',
        'hours_worked',
        'workingdays_hours_worked',
        'wage_in_cents',
        'wage_bonus_in_cents',
        'bonus_months_in_cents',
        'bonus_in_cents',
        'gross_in_cents',
        'tax_refund_in_cents',
        'net_in_cents',
        'planned_working_hours_formatted',
        'tax_refund_formatted',
        'wage_bonus_formatted',
        'wage_formatted',
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
            $model->workingdays_worked = 0;
            $model->workingdays_hours_worked = 0;
            $model->hours_worked = 0;

            $model->wage_in_cents = 20 * 100;
            $model->wage_bonus_in_cents = 0;

            $model->bonus_months_in_cents = 0;
            $model->bonus_in_cents = 0;
            $model->gross_in_cents = 0;
            $model->tax_refund_in_cents = 0;
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
            ->select(DB::raw('SUM(hours_worked) AS hours_worked'), DB::raw('SUM(workingdays_hours_worked) AS workingdays_hours_worked'), DB::raw('SUM(days_worked) AS days_worked'), DB::raw('SUM(workingdays_worked) AS workingdays_worked'), DB::raw('SUM(gross_in_cents) AS gross_in_cents'), DB::raw('SUM(net_in_cents) AS net_in_cents'), DB::raw('SUM(bonus_in_cents) AS bonus_in_cents'))
            ->where('year_id', $this->id)
            ->groupBy('year_id')
            ->first();

        $this->days_worked = $data->days_worked ?? 0;
        $this->workingdays_worked = $data->workingdays_worked ?? 0;
        $this->hours_worked = $data->hours_worked ?? 0;
        $this->workingdays_hours_worked = $data->workingdays_hours_worked ?? 0;
        $this->bonus_months_in_cents = $data->bonus_in_cents ?? 0;
        $this->bonus_in_cents = $this->bonus_months_in_cents + ($this->wage_bonus_in_cents * $this->hours_worked);
        $this->gross_in_cents = $data->gross_in_cents;
        $this->net_in_cents = ($data->net_in_cents ?? 0) + $this->tax_refund_in_cents;

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

    public function getBonusFormattedAttribute() : string
    {
        return number_format(($this->attributes['bonus_in_cents'] / 100), 2, ',', '.');
    }

    public function setBonusFormattedAttribute(string $value) : void
    {
        $this->bonus_in_cents = str_replace(',', '.', $value) * 100;
        Arr::forget($this->attributes, 'bonus_formatted');
    }

    public function getBonusMonthsFormattedAttribute() : string
    {
        return number_format(($this->attributes['bonus_months_in_cents'] / 100), 2, ',', '.');
    }

    public function getGrossFormattedAttribute() : string
    {
        return number_format(($this->attributes['gross_in_cents'] / 100), 2, ',', '.');
    }

    public function getHolidayHoursWorkedAttribute() : float
    {
        return ($this->attributes['hours_worked'] - $this->attributes['workingdays_hours_worked']);
    }

    public function getHoursWorkedFormattedAttribute() : string
    {
        return number_format($this->hours_worked, 2, ',', '.');
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

    public function getNetFormattedAttribute() : string
    {
        return number_format(($this->attributes['net_in_cents'] / 100), 2, ',', '.');
    }

    public function getPathAttribute()
    {
        return '/work/year/' . $this->id;
    }

    public function getPlannedWorkingHoursDayAttribute() : float
    {
        if ($this->attributes['planned_working_hours'] == 0 || $this->attributes['available_working_days'] == 0) {
            return 0;
        }

        return ($this->attributes['planned_working_hours'] / $this->attributes['available_working_days']);
    }

    public function getPlannedWorkingHoursDayFormattedAttribute() : string
    {
        return number_format($this->planned_working_hours_day, 2, ',', '.');
    }

    public function getPlannedWorkingHoursFormattedAttribute() : string
    {
        return number_format($this->planned_working_hours, 2, ',', '');
    }

    public function setPlannedWorkingHoursFormattedAttribute(string $value) : void
    {
        $this->planned_working_hours = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'planned_working_hours_formatted');
    }

    public function getTaxRefundFormattedAttribute() : string
    {
        return number_format(($this->attributes['tax_refund_in_cents'] / 100), 2, ',', '.');
    }

    public function setTaxRefundFormattedAttribute(string $value) : void
    {
        $this->tax_refund_in_cents = str_replace(',', '.', $value) * 100;
        Arr::forget($this->attributes, 'tax_refund_formatted');
    }

    public function getWageFormattedAttribute() : string
    {
        return number_format(($this->attributes['wage_in_cents'] / 100), 2, ',', '.');
    }

    public function setWageFormattedAttribute(string $value) : void
    {
        $this->wage_in_cents = str_replace(',', '.', $value) * 100;
        Arr::forget($this->attributes, 'wage_formatted');
    }

    public function getWageBonusFormattedAttribute() : string
    {
        return number_format(($this->attributes['wage_bonus_in_cents'] / 100), 2, ',', '.');
    }

    public function setWageBonusFormattedAttribute(string $value) : void
    {
        $this->wage_bonus_in_cents = str_replace(',', '.', $value) * 100;
        Arr::forget($this->attributes, 'wage_bonus_formatted');
    }

    public function getWageTotalInCentsAttribute() : float
    {
        return ($this->wage_in_cents * $this->hours_worked);
    }

    public function getWageTotalFormattedAttribute() : string
    {
        return number_format(($this->wage_total_in_cents / 100), 2, ',', '.');
    }




    public function months() : HasMany
    {
        return $this->hasMany(Month::class);
    }
}
