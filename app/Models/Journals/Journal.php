<?php

namespace App\Models\Journals;

use App\Models\Journals\Gratitude\Gratitude;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Journal extends Model
{
    protected $appends = [
        'path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'date',
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
            $model->name = 'Eintrag vom ' . $model->date->format('d.m.Y');

            return true;
        });

        static::updating(function($model)
        {
            return true;
        });
    }

    public function getPathAttribute()
    {
        return '/journal/' . $this->id;
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gratitudes() : HasMany
    {
        return $this->hasMany(Gratitude::class, 'journal_id');
    }
}
