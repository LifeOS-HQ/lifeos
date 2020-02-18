<?php

namespace App\Models\Lifeareas;

use App\Models\Lifeareas\Lifearea;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scale extends Model
{
    protected $appends = [
        'is_deletable',
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

    protected $table = 'lifearea_scale';

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
        return false;
    }

    public function getIsDeletableAttribute() : bool
    {
        return $this->isDeletable();
    }

    public function getPathAttribute()
    {
        return '/lifearea/' . $this->lifearea_id . '/scale/' . $this->id;
    }

    public function lifearea() : BelongsTo
    {
        return $this->belongsTo(Lifearea::class, 'lifearea_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
