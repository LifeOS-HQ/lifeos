<?php

namespace App\Models\Workouts;

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

    public function exercise_histories() : HasMany
    {
        return $this->hasMany(\App\Models\Workouts\Exercises\History::class, 'workout_history_id', 'id');
    }

    public function sets() : HasMany
    {
        return $this->hasMany(\App\Models\Workouts\Sets\History::class, 'workout_history_id');
    }
}
