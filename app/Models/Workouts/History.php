<?php

namespace App\Models\Workouts;

use Carbon\CarbonInterval;
use App\Traits\BelongsToUser;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class History extends Model
{
    use BelongsToUser,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'fitness.workouts.histories';

    protected $appends = [
        'start_at_formatted',
        'distance_accum_formatted',
        'duration_total_accum_formatted',
        'ascent_accum_formatted',
        'calories_accum_formatted',
        'heart_rate_avg_formatted',
        'power_avg_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'start_at',
        'end_at',
    ];

    protected $guarded = [
        'id',
    ];

    public $table = 'workout_histories';

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
            return true;
        });

        static::created(function($model)
        {
            return true;
        });

        static::updating(function($model)
        {
            return true;
        });

        static::deleting(function($model)
        {
            foreach($model->exercise_histories as $exercise_history) {
                foreach ($exercise_history->sets as $key => $set) {
                    $set->delete();
                }
                $exercise_history->delete();
            }

            return true;
        });
    }

    public static function updateOrCreateFromWahoo(Workout $workout, array $wahoo_workout): self
    {
        $start_at = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $wahoo_workout['starts'], 'UTC');
        $end_at = $start_at->copy()->addSeconds($wahoo_workout['workout_summary']['duration_total_accum']);

        $workout_history = self::updateOrCreate(
            [
                'user_id' => $workout->user_id,
                'workout_id' => $workout->id,
                'source_slug' => 'wahoo',
                'source_id' => $wahoo_workout['id'],
            ],
            [
                'start_at' => $start_at,
                'end_at' => $end_at,
                'ascent_accum' => $wahoo_workout['workout_summary']['ascent_accum'],
                'cadence_avg' => $wahoo_workout['workout_summary']['cadence_avg'],
                'calories_accum' => $wahoo_workout['workout_summary']['calories_accum'],
                'distance_accum' => $wahoo_workout['workout_summary']['distance_accum'],
                'duration_active_accum' => $wahoo_workout['workout_summary']['duration_active_accum'],
                'duration_paused_accum' => $wahoo_workout['workout_summary']['duration_paused_accum'],
                'duration_total_accum' => $wahoo_workout['workout_summary']['duration_total_accum'],
                'heart_rate_avg' => $wahoo_workout['workout_summary']['heart_rate_avg'],
                'power_bike_np_last' => $wahoo_workout['workout_summary']['power_bike_np_last'] ?? 0,
                'power_bike_tss_last' => $wahoo_workout['workout_summary']['power_bike_tss_last'] ?? 0,
                'power_avg' => $wahoo_workout['workout_summary']['power_avg'],
                'speed_avg' => $wahoo_workout['workout_summary']['speed_avg'],
                'work_accum' => $wahoo_workout['workout_summary']['work_accum'],
            ]
        );

        return $workout_history;
    }

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Training',
                'plural' => 'Trainings',
            ],
        ];
    }

    public function isDeletable() : bool
    {
        return true;
    }

    protected function getAvailablePaths() : array
    {
        return [
            // 'create_path',
            // 'edit_path',
            'index_path',
            'path',
        ];
    }

    public function getStartAtFormattedAttribute() : string
    {
        return $this->start_at->format('d.m.Y H:i');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'workout' => $this->workout_id,
            'history' => $this->id,
        ];
    }

    public function getDistanceAccumFormattedAttribute(): string
    {
        return number_format($this->distance_accum / 1000, 3, ',', '.');
    }

    public function getDurationTotalAccumFormattedAttribute(): string
    {
        return CarbonInterval::createFromFormat('s', (int) $this->duration_total_accum)->cascade()->forHumans(short: true);
    }

    public function getAscentAccumFormattedAttribute(): string
    {
        return number_format($this->ascent_accum, 0, ',', '.');
    }

    public function getCaloriesAccumFormattedAttribute(): string
    {
        return number_format($this->calories_accum, 0, ',', '.');
    }

    public function getHeartRateAvgFormattedAttribute(): string
    {
        return number_format($this->heart_rate_avg, 0, ',', '.');
    }

    public function getPowerAvgFormattedAttribute(): string
    {
        return number_format($this->power_avg, 0, ',', '.');
    }

    public function exercise_histories() : HasMany
    {
        return $this->hasMany(\App\Models\Workouts\Exercises\History::class, 'workout_history_id', 'id');
    }

    public function sets() : HasMany
    {
        return $this->hasMany(\App\Models\Workouts\Sets\History::class, 'workout_history_id');
    }
}
