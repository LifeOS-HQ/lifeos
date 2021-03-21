<?php

namespace App\Models\Workouts;

use App\Models\Exercises\Exercise;
use App\Models\Workouts\History;
use App\Traits\BelongsToUser;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workout extends Model
{
    use BelongsToUser,
        HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'fitness.workouts';

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
            $model->exercises()->delete();
            $model->histories()->delete();
            $model->sets()->delete();

            return true;
        });
    }

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Trainingsplan',
                'plural' => 'Trainingspläne',
            ],
        ];
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'workout' => $this->id,
        ];
    }

    public function exercises() : HasMany
    {
        return $this->hasMany(\App\Models\Workouts\Exercises\Exercise::class, 'workout_id');
    }

    public function histories() : hasMany
    {
        return $this->hasMany(History::class, 'workout_id');
    }

    public function sets() : HasMany
    {
        return $this->hasMany(Set::class, 'workout_id');
    }

    public function scopeSearch(Builder $query, $value) : Builder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->where('name', 'LIKE', '%' . $value . '%');
    }
}
