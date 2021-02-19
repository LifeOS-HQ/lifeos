<?php

namespace App\Models\Lifeareas\Levels\Goals;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Goal extends Model
{
    use BelongsToUser;

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'user_id',
        'lifearea_id',
        'level_id',
        'data_attribute_id',
        'start',
        'start_formatted',
        'end',
        'end_formatted',
    ];

    public $table = 'lifearea_level_goal';

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
        return route('lifeareas.levels.goals.show', [
            'goal' => $this->id,
            'level' => $this->level_id,
        ]);
    }

    public function setEndFormattedAttribute($value) : void
    {
        $this->attributes['end'] = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'end_formatted');

    }

    public function setStartFormattedAttribute($value) : void
    {
        $this->attributes['start'] = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'start_formatted');
    }
}