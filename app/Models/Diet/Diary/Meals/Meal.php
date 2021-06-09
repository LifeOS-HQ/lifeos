<?php

namespace App\Models\Diet\Diary\Meals;

use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class Meal extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'diet.days.meals';

    protected $appends = [
        'at_formatted',
        'foods_path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'at',
    ];

    protected $fillable = [
        'user_id',
        'day_id',
        'name',
        'oder_by',
        'at_formatted',
        'rating_points',
        'rating_comment',
    ];

    protected $table = 'diet_days_meals';

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
                'singular' => 'Mahlzeit',
                'plural' => 'Mahlzeiten',
            ],
        ];
    }

    public function foods() : HasMany
    {
        return $this->hasMany(\App\Models\Diet\Diary\Meals\Food::class, 'diet_days_meal_id');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'day' => $this->day_id,
            'meal' => $this->id,
        ];
    }

    public function getFoodsPathAttribute() : string
    {
        return \App\Models\Diet\Diary\Meals\Food::indexPath([
            'diet_days_meal_id' => $this->id,
        ]);
    }

    public function getAtFormattedAttribute() : string
    {
        if (is_null($this->at)) {
            return '';
        }

        return $this->at->format('d.m.Y');
    }

    public function setAtFormattedAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['at'] = null;
        }
        else {
            $this->attributes['at'] = Carbon::createFromFormat('d.m.Y', $value)->setTime(0, 0, 0);
        }
        Arr::forget($this->attributes, 'at_formatted');
    }
}