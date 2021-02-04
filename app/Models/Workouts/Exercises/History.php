<?php

namespace App\Models\Workouts\Exercises;

use App\Models\Exercises\Exercise;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class History extends Model
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
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getPathAttribute()
    {
        return '/history/' . $this->id;
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
