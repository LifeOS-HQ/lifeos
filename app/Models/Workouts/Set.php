<?php

namespace App\Models\Workouts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Set extends Model
{
    protected $appends = [
        //
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
        'weight_in_g',
        'reps_count',
        'order',
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

    public function isDeletable() : bool
    {
        return true;
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
