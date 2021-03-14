<?php

namespace App\Models\Lifeareas;

use App\Models\Lifeareas\Scale;
use App\Models\Reviews\Lifearea as ReviewLifearea;
use App\Traits\BelongsToUser;
use App\User;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lifearea extends Model
{
    use BelongsToUser,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'lifeareas';

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
            $model->createScales();

            return true;
        });

        static::deleting(function($model)
        {
            $model->ratings()->delete();
            $model->scales()->delete();

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
                'singular' => 'Lebensbereich',
                'plural' => 'Lebensbereiche',
            ],
        ];
    }

    protected function createScales()
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->scales()->create([
                'user_id' => $this->user_id,
                'value' => $i,
            ]);
        }
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getRatingsAvgAttribute() : float
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function getRatingsAvgFormattedAttribute() : string
    {
        $ratings_avg = $this->ratings_avg;

        return ($ratings_avg == 0 ? '-' : number_format($this->ratings_avg, 2, ',', '.'));
    }

    public function activities() : HasMany
    {
        return $this->hasMany(\App\Models\Activities\Activity::class, 'lifearea_id');
    }

    public function ratings() : HasMany
    {
        return $this->hasMany(ReviewLifearea::class, 'lifearea_id');
    }

    public function scales() : HasMany
    {
        return $this->hasMany(Scale::class, 'lifearea_id')->orderBy('value', 'ASC');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
