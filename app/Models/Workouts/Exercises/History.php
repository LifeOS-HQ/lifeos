<?php

namespace App\Models\Workouts\Exercises;

use App\Models\Exercises\Exercise;
use App\Traits\BelongsToUser;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class History extends Model
{
    use BelongsToUser,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'fitness.workouts.histories.exercises';

    protected $appends = [
        'sets_index_path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $guarded = [
        'id',
    ];

    public $table = 'workout_exercise_histories';

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
            $model->sets()->delete();

            return true;
        });
    }

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Set',
                'plural' => 'Sets',
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

    public function getSetsIndexPathAttribute() : string
    {
        return route(\App\Models\Workouts\Sets\History::ROUTE_NAME . '.index', [
            'history' => $this->workout_history_id,
            'exercise_history' => $this->id,
        ]);
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'history' => $this->workout_history_id,
            'exercise_history' => $this->id,
        ];
    }

    public function exercise() : BelongsTo
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }

    public function sets() : HasMany
    {
        return $this->hasMany(\App\Models\Workouts\Sets\History::class, 'workout_exercise_history_id');
    }
}
