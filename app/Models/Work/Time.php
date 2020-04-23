<?php

namespace App\Models\Work;

use App\Models\Work\Month;
use App\Support\Holidays;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Time extends Model
{
    protected $appends = [
        'date_formatted',
        'start_at_formatted',
        'end_at_formatted',
        'seconds_formatted',
        'seconds_break_formatted'
    ];

    protected $dates = [
        'start_at',
        'end_at',
    ];

    protected $guarded = [
        'id'
    ];

    protected $table = 'working_times';

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
            $model->setSeconds();
            $model->seconds_break = $model->seconds_break ?? 0;
            $model->is_workingday = Holidays::isWorkingday($model->start_at);

            return true;
        });

        static::updating(function($model)
        {
            $model->setSeconds();
            $model->is_workingday = Holidays::isWorkingday($model->start_at);

            return true;
        });
    }

    public static function createFromCsv(int $userId, Month $month, array $data) : self
    {
        $isEnded = ($data[3] != '0000-00-00 00:00:00');
        $isDeleted = ($data[9] != '0000-00-00 00:00:00');

        if ($isDeleted) {
            self::where('user_id', $userId)
                ->where('foreign_id', $data[0])
                ->delete();

            return new Time();
        }

        $startAt = new Carbon($data[2]);
        $endAt = ($isEnded ? new Carbon($data[3]) : null);
        $seconds_break = Time::fromIndustryHours($data[4]);

        $values = [
            'user_id' => 1,
            'foreign_id' => $data[0],
            'start_at' => $startAt,
            'end_at' => $endAt,
            'seconds_break' => $seconds_break,
        ];

        $model = $month->times()->updateOrCreate([
            'foreign_id' => $data[0],
        ], $values);

        return $model;
    }

    public static function toIndustryHours(int $seconds)
    {
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60);

        return round($hours + ($mins/60) + ($secs / 3600), 2);
    }

    public static function fromIndustryHours(float $industryHours) : int
    {
        $hours = intval($industryHours);
        $minutes = fmod($industryHours, 1) * 60;

        return (($hours * 60 * 60) + $minutes * 60);

        return sprintf('%02s:%02s:00', $hours, $minutes);
    }

    public function getIndustryHoursAttribute() : float
    {
        return self::toIndustryHours($this->attributes['seconds'] - $this->attributes['seconds_break']);
    }

    public function getSecondsFormattedAttribute()
    {
        return number_format(self::toIndustryHours($this->attributes['seconds'] - $this->attributes['seconds_break']), 2, ',', '');
    }

    public function getSecondsBreakFormattedAttribute()
    {
        return number_format(self::toIndustryHours($this->attributes['seconds_break']), 2, ',', '');
    }

    public function setSeconds()
    {
        $this->attributes['seconds'] = is_null($this->end_at) ? 0 : $this->end_at->diffInSeconds($this->start_at);
    }

    public function getDateFormattedAttribute() : string
    {
        return $this->start_at->format('d.m.Y');
    }

    public function getStartAtFormattedAttribute() : string
    {
        return $this->start_at->format('d.m.Y H:i');
    }

    public function getEndAtFormattedAttribute() : string
    {
        if (is_null($this->attributes['end_at'])) {
            return '-';
        }

        return $this->end_at->format('d.m.Y H:i');
    }

    public function month() : BelongsTo
    {
        return $this->belongsTo(Month::class, 'month_id');
    }
}
