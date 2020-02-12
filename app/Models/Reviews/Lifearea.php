<?php

namespace App\Models\Reviews;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $table = 'lifearea_review';

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
        return '/review/' . $this->review_id . '/lifearea/' . $this->id;
    }

    public function lifearea() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Lifeareas\Lifearea::class, 'lifearea_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
