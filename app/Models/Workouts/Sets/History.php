<?php

namespace App\Models\Workouts\Sets;

use App\Traits\BelongsToUser;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class History extends Model
{
    use BelongsToUser,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'fitness.workouts.histories.exercises.sets';

    protected $appends = [
        'weight_in_kg',
        'weight_in_kg_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'is_completed',
        'order',
        'reps_count',
        'user_id',
        'weight_in_g',
        'weight_in_kg_formatted',
        'workout_exercise_history_id',
        'workout_history_id',
    ];

    public $table = 'workout_set_histories';

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
            'history' => $this->workout_history_id,
            'exercise_history' => $this->workout_exercise_history_id,
            'set_history' => $this->id,
        ];
    }
}
