<?php

namespace App\Models\Diet\Diary;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use App\Models\Services\Data\Value;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use App\Models\Services\Data\Attributes\Attribute;
use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Day extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'diet.days';

    protected $appends = [
        'at_formatted',
        'meals_path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'at',
    ];

    protected $fillable = [
        'user_id',
        'at_formatted',
        'rating_points',
        'rating_comment',
    ];

    protected $table = 'diet_days';

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if (is_null($model->at)) {
                $model->at = now();
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

    public function populateMealsFromLastDay(): self
    {
        $last_day = self::with([
            'meals.foods'
        ])
            ->where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->whereHas('meals')
            ->latest('at')
            ->first();

        if (is_null($last_day) || $last_day->meals->count() == 0) {
            return $this;
        }

        return $this->populateMealsFrom($last_day);
    }

    public function populateMealsFromLastWeekday(): self
    {
        $last_day = self::with([
            'meals.foods'
        ])
            ->where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->whereRaw('WEEKDAY(at) = ?', [$this->at->dayOfWeekIso - 1])
            ->latest('at')
            ->first();

        if (is_null($last_day) || $last_day->meals->count() == 0) {
            return $this;
        }

        return $this->populateMealsFrom($last_day);
    }

    private function populateMealsFrom(self $last_day): self
    {
        foreach ($last_day->meals as $last_day_meal) {
            $meal = $this->meals()->create([
                'at' => is_null($last_day_meal->at) ? $this->at : $last_day_meal->at->setDateFrom($this->at),
                'rating_comments' => null,
                'name' => $last_day_meal->name,
                'order_by' => $last_day_meal->order_by,
                'user_id' => $this->user_id,
            ]);

            foreach ($last_day_meal->foods as $last_day_meal_food) {
                $meal->foods()->create([
                    'amount' => $last_day_meal_food->amount,
                    'food_id' => $last_day_meal_food->food_id,
                    'meal_id' => $meal->id,
                    'user_id' => $this->user_id,
                ]);
            }
        }

        return $this;
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

    public function cache(): void
    {
        $this->loadMissing([
            'meals',
        ]);

        $this->setNutritionValues()
            ->saveQuietly();

        $this->updateOrCreateFoodAttributes();
    }

    private function setNutritionValues(): self
    {
        $this->calories = 0;
        $this->carbohydrate = 0;
        $this->fat = 0;
        $this->protein = 0;

        foreach ($this->meals as $meal) {
            $this->calories += $meal->calories;
            $this->carbohydrate += $meal->carbohydrate;
            $this->fat += $meal->fat;
            $this->protein += $meal->protein;
        }

        return $this;
    }

    private function updateOrCreateFoodAttributes(): self
    {
        $this
            ->updateOrCreateEnergyAttribute()
            ->updateOrCreateCarbohydratesAttribute()
            ->updateOrCreateFatAttribute()
            ->updateOrCreateProteinAttribute();

        return $this;
    }

    private function updateOrCreateEnergyAttribute(): self
    {
        $attribute = Attribute::where('slug', 'energy')->first();

        if (is_null($attribute)) {
            return $this;
        }

        $attributes = [
            'user_id' => $this->user_id,
            'attribute_id' => $attribute->id,
            'service_id' => Service::where('slug', 'exist')->first()->id,
            'at' => $this->at->startOfDay(),
        ];

        $values = [
            'raw' => $this->kilojoules,
        ];

        Value::updateOrCreate($attributes, $values);

        return $this;
    }

    private function updateOrCreateCarbohydratesAttribute(): self
    {
        $attribute = Attribute::where('slug', 'carbohydrates')->first();

        if (is_null($attribute)) {
            return $this;
        }

        $attributes = [
            'user_id' => $this->user_id,
            'attribute_id' => $attribute->id,
            'service_id' => Service::where('slug', 'exist')->first()->id,
            'at' => $this->at->startOfDay(),
        ];

        $values = [
            'raw' => $this->carbohydrate,
        ];

        Value::updateOrCreate($attributes, $values);

        return $this;
    }

    private function updateOrCreateFatAttribute(): self
    {
        $attribute = Attribute::where('slug', 'fat')->first();

        if (is_null($attribute)) {
            return $this;
        }

        $attributes = [
            'user_id' => $this->user_id,
            'attribute_id' => $attribute->id,
            'service_id' => Service::where('slug', 'exist')->first()->id,
            'at' => $this->at->startOfDay(),
        ];

        $values = [
            'raw' => $this->fat,
        ];

        Value::updateOrCreate($attributes, $values);

        return $this;
    }

    private function updateOrCreateProteinAttribute(): self
    {
        $attribute = Attribute::where('slug', 'protein')->first();

        if (is_null($attribute)) {
            return $this;
        }

        $attributes = [
            'user_id' => $this->user_id,
            'attribute_id' => $attribute->id,
            'service_id' => Service::where('slug', 'exist')->first()->id,
            'at' => $this->at->startOfDay(),
        ];

        $values = [
            'raw' => $this->protein,
        ];

        Value::updateOrCreate($attributes, $values);

        return $this;
    }

    public function getKilojoulesAttribute(): float
    {
        return $this->calories * 4.184;
    }

    public function meals() : HasMany
    {
        return $this->hasMany(\App\Models\Diet\Diary\Meals\Meal::class, 'day_id');
    }

    public function getMealsPathAttribute() : string
    {
        return \App\Models\Diet\Diary\Meals\Meal::indexPath([
            'day_id' => $this->id,
        ]);
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'day' => $this->id
        ];
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
