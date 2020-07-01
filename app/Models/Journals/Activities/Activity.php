<?php

namespace App\Models\Journals\Activities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

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

    protected $table = 'activity_journal';

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
            if (is_null($model->user_id)) {
                $model->user_id = Auth::user()->id;
            }

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
        return '/journal/' . $this->journal_id . '/activity/' . $this->id;
    }

    public function activity() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Activities\Activity::class, 'activity_id');
    }
}
