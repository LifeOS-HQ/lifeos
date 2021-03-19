<?php

namespace App\Models\Workouts\Exercises;

use App\Models\Workouts\Set;
use App\Traits\BelongsToUser;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'fitness.workouts.exercises';

    protected $appends = [
        'sets_index_path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'user_id',
        'exercise_id',
        'workout_id',
        'goal_type',
        'goal_target',
        'order',
    ];

    public $table = 'workout_exercise';

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
                'singular' => 'Übung',
                'plural' => 'Übungen',
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
        return route(Set::ROUTE_NAME . '.index', [
            'workout' => $this->workout_id,
            'exercise' => $this->id,
        ]);
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'workout' => $this->workout_id,
            'exercise' => $this->id
        ];
    }

    public function exercise() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Exercises\Exercise::class, 'exercise_id');
    }

    public function sets() : HasMany
    {
        return $this->hasMany(\App\Models\Workouts\Set::class, 'workout_exercise_id');
    }
}
