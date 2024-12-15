<?php

namespace App\Models\Obstacles;

use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obstacle extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'obstacles';

    protected $appends = [
        'edit_path',
        'path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'alchemized_day_id',
        'challenge',
        'created_day_id',
        'is_active',
        'level',
        'loot',
        'obstacle',
        'outcome',
        'plan',
        'title',
        'user_id',
        'wish',
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
            if (empty($model->created_day_id)) {
                $model->setCreatedDayId();
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

    public function setCreatedDayId(): self
    {
        $this->created_day_id = \App\Models\Days\Day::firstOrCreate([
            'user_id' => $this->user_id,
            'date' => now()->format('Y-m-d'),
        ])->id;

        return $this;
    }

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Hindernis',
                'plural' => 'Hindernisse',
            ],
        ];
    }
}
