<?php

namespace App\Models\Workouts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class History extends Model
{
    protected $appends = [
        // 'path',
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
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getPathAttribute()
    {
        return route('workouts.histories.show', [
            'workout' => $this->workout_id,
            'history' => $this->id,
        ]);
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
