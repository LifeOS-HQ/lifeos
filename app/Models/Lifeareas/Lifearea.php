<?php

namespace App\Models\Lifeareas;

use App\Models\Lifeareas\Scale;
use App\Models\Reviews\Lifearea as ReviewLifearea;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lifearea extends Model
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

    protected function createScales()
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->scales()->create([
                'user_id' => $this->user_id,
                'value' => $i,
            ]);
        }
    }

    public function getPathAttribute()
    {
        return '/lifearea/' . $this->id;
    }

    public function isDeletable() : bool
    {
        return true;
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
