<?php

namespace App\Models\Diet\Foods;

use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Packaging extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'diet.foods.packagings';

    protected $appends = [
        'amount_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'amount_formatted',
    ];

    protected $table = 'food_packaging';

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
                'singular' => 'Verpackung',
                'plural' => 'Verpackungen',
            ],
        ];
    }

    public function getAmountFormattedAttribute() : string
    {
        return number_format($this->attributes['amount'], 2, ',', '.');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'food' => $this->food_id,
            'packaging' => $this->id,
        ];
    }

    public function setAmountFormattedAttribute($value) : void
    {
        $this->attributes['amount'] = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'amount_formatted');
    }
}
