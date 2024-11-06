<?php

namespace App\Models\Behaviours;

use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'behaviours.histories';

    protected $appends = [
        'end_at_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'start_at',
        'end_at',
    ];

    protected $fillable = [
        'behaviour_id',
        'user_id',
        'start_at',
        'end_at',
        'comment',
    ];

    protected $table = 'behaviours_histories';

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

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Verlauf',
                'plural' => 'Verläufe',
            ],
        ];
    }

    public function getEndAtFormattedAttribute() : string
    {
        return $this->end_at->format('d.m.Y H:i');
    }

    public function getStartAtFormattedAttribute() : string
    {
        return $this->end_at->format('d.m.Y H:i');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'behaviour' => $this->behaviour_id,
            'history' => $this->id,
        ];
    }
}
