<?php

namespace App\Models\Work;

use App\Models\Work\Time;
use App\Models\Work\Year;
use App\Support\Holidays;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Month extends Model
{
    protected $casts = [
        'hours_worked' => 'decimal:2',
    ];

    protected $dates = [
        'date',
    ];

    protected $guarded = [
        'id'
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
        $this->hours_worked = Time::toIndustryHours(is_null($data->seconds) ? 0 : ($data->seconds - $data->seconds_break));

        return $this;
    }

    public function year() : BelongsTo
    {
        return $this->belongsTo(Year::class, 'year_id');
    }

    public function times() : HasMany
    {
        return $this->hasMany(Time::class);
    }
}
