<?php

namespace App\Models\Diet\Plans;

use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class Plan extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'diet.plans';

    protected $appends = [
        'valid_from_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'valid_from',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'valid_from_formatted',
        'is_active',
    ];

    protected $table = 'diet_plans';

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
                'singular' => 'Ernährungsplan',
                'plural' => 'Ernährungspläne',
            ],
        ];
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'plan' => $this->id,
        ];
    }

    public function getValidFromFormattedAttribute() : string
    {
        if (is_null($this->valid_from)) {
            return '';
        }

        return $this->valid_from->format('d.m.Y');
    }

    public function setValidFromFormattedAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['valid_from'] = null;
        }
        else {
            $this->attributes['valid_from'] = Carbon::createFromFormat('d.m.Y', $value)->setTime(0, 0, 0);
        }
        Arr::forget($this->attributes, 'valid_from_formatted');
    }
}
