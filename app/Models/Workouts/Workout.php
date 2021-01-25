<?php

namespace App\Models\Workouts;

use App\Models\Exercises\Exercise;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workout extends Model
{
    protected $appends = [
        'path',
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
        return '/workout/' . $this->id;
    }

    public function exercises() : BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'workout_exercise')
            ->withTimestamps()
            ->withPivot([
                'order',
                'goal_type',
                'goal_target',
            ]);
    }

    public function sets() : HasMany
    {
        return $this->hasMany(Set::class, 'workout_id');
    }
}
