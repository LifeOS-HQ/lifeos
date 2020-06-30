<?php

namespace App\Models\Activities;

use App\Models\Lifeareas\Lifearea;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
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
        return '/activity/' . $this->id;
    }

    public function lifearea() : BelongsTo
    {
        return $this->belongsTo(Lifearea::class, 'lifearea_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeLifearea(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        if ($value == 0) {
            $query->whereNull('lifearea_id');
        }

        return $query->where('lifearea_id', $value);
    }

    public function scopeSearch(Builder $query, $value) : Builder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->where('title', 'LIKE', '%' . $value . '%');
    }
}
