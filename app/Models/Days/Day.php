<?php

namespace App\Models\Days;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Day extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'days';

    protected $appends = [
        'date_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'date',
    ];

    protected $fillable = [
        'user_id',
        'date',
        'date_formatted',
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

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Tag',
                'plural' => 'Tage',
            ],
        ];
    }

    public function getDateFormattedAttribute() : string
    {
        return $this->date->format('d.m.Y');
    }

    public function setDateFormattedAttribute($value) : void
    {
        $this->attributes['date'] = Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
        Arr::forget($this->attributes, 'date_formatted');
    }
}
