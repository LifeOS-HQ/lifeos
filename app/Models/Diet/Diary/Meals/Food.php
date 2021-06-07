<?php

namespace App\Models\Diet\Diary\Meals;

use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class Food extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'diet.days.meals.foods';

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
        'user_id',
        'food_id',
        'diet_days_meal_id',
        'amount',
        'amount_formatted',
        'order_by',
    ];

    protected $table = 'diet_days_meals_food';

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
                'singular' => 'Nahrungsmittel',
                'plural' => 'Nahrungsmittel',
            ],
        ];
    }

    public function food() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Diet\Foods\Food::class, 'food_id');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'meal' => $this->diet_days_meal_id,
            'food' => $this->id,
        ];
    }

    public function getAmountFormattedAttribute() : string
    {
        return number_format($this->attributes['amount'], 2, ',', '.');
    }

    public function setAmountFormattedAttribute($value) : void
    {
        $this->attributes['amount'] = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'amount_formatted');
    }
}
