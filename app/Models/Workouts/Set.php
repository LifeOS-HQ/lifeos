<?php

namespace App\Models\Workouts;

use App\Traits\BelongsToUser;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class Set extends Model
{
    use BelongsToUser,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'fitness.workouts.exercises.sets';

    protected $appends = [
        'weight_in_kg',
        'weight_in_kg_formatted',
    ];

    protected function casts(): array
    {
        return [];
    }


    protected $fillable = [
        'user_id',
        'workout_exercise_id',
        'exercise_id',
        'workout_id',
        'weight_in_g',
        'reps_count',
        'order',
        'weight_in_kg_formatted',
    ];

    public $table = 'workout_set';

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

    public function getWeightInKgAttribute() : float
    {
        return $this->attributes['weight_in_g'] / 1000;
    }

    public function getWeightInKgFormattedAttribute() : string
    {
        return number_format($this->weight_in_kg, 2, ',', '');
    }

    public function setWeightInKgFormattedAttribute(string $value) : void
    {
        $this->attributes['weight_in_g'] = str_replace(',', '.', $value) * 1000;
        Arr::forget($this->attributes, 'weight_in_kg_formatted');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'workout' => $this->workout_id,
            'exercise' => $this->workout_exercise_id,
            'set' => $this->id,
        ];
    }

    public function exercise() : BelongsTo
    {
        return $this->belongsTo(App\Models\Exercises\Exercise::class, 'exercise_id');
    }

    public function workout() : BelongsTo
    {
        return $this->belongsTo(App\Models\Workouts\Workout::class, 'workout_id');
    }
}
