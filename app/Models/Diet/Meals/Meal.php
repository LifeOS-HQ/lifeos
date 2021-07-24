<?php

namespace App\Models\Diet\Meals;

use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meal extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'diet.meals';

    protected $appends = [
        'foods_path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'user_id',
        'name',
        'rating_points',
        'rating_comment',
    ];

    protected $table = 'diet_meals';

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

    public function cache() : void
    {
        $this->loadMissing([
            'foods',
        ]);

        $this->setNutritionValues()
            ->saveQuietly();
    }

    public function setNutritionValues() : self
    {
        $this->calories = 0;
        $this->carbohydrate = 0;
        $this->fat = 0;
        $this->protein = 0;

        foreach ($this->foods as $food) {
            $this->calories += $food->calories;
            $this->carbohydrate += $food->carbohydrate;
            $this->fat += $food->fat;
            $this->protein += $food->protein;
        }

        return $this;
    }

    public function foods() : HasMany
    {
        return $this->hasMany(\App\Models\Diet\Meals\Food::class, 'diet_meal_id');
    }

    public function getFoodsPathAttribute() : string
    {
        return \App\Models\Diet\Meals\Food::indexPath([
            'diet_meal_id' => $this->id,
        ]);
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'meal' => $this->id,
        ];
    }

}
