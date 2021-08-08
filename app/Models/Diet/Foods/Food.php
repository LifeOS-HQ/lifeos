<?php

namespace App\Models\Diet\Foods;

use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Food extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'diet.foods';

    const DEFAULT_MICRONUTRIENTS = [
        //
    ];

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
        'name',
        'calories_formatted',
        'carbohydrate_formatted',
        'fat_formatted',
        'protein_formatted',
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

        static::deleting(function($model)
        {
            $model->packagings()->delete();

            return true;
        });
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function cacheCalories() : void
    {
        $this->attributes['calories'] = 0;
        $this->attributes['calories'] += $this->attributes['carbohydrate'] * 4;
        $this->attributes['calories'] += $this->attributes['fat'] * 9;
        $this->attributes['calories'] += $this->attributes['protein'] * 4;
    }

    public function packagings() : HasMany
    {
        return $this->hasMany(\App\Models\Diet\Foods\Packaging::class, 'food_id');
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

    public function getRouteParameterAttribute() : array
    {
        return [
            'food' => $this->id
        ];
    }

    public function getPackagingPathAttribute() : string
    {
        return route('diet.foods.packagings.index', [
            'food' => $this->id,
        ]);
    }

    public function getCaloriesFormattedAttribute() : string
    {
        return number_format($this->attributes['calories'] * 100, 2, ',', '.');
    }

    public function getCarbohydrateFormattedAttribute() : string
    {
        return number_format($this->attributes['carbohydrate'] * 100, 2, ',', '.');
    }

    public function getFatFormattedAttribute() : string
    {
        return number_format($this->attributes['fat'] * 100, 2, ',', '.');
    }

    public function getProteinFormattedAttribute() : string
    {
        return number_format($this->attributes['protein'] * 100, 2, ',', '.');
    }

    public function setCaloriesFormattedAttribute($value) : void
    {
        $this->attributes['calories'] = (str_replace(',', '.', $value) / 100);
        Arr::forget($this->attributes, 'calories_formatted');
    }

    public function setCarbohydrateFormattedAttribute($value) : void
    {
        $this->attributes['carbohydrate'] = (str_replace(',', '.', $value) / 100);
        Arr::forget($this->attributes, 'carbohydrate_formatted');
    }

    public function setFatFormattedAttribute($value) : void
    {
        $this->attributes['fat'] = (str_replace(',', '.', $value) / 100);
        Arr::forget($this->attributes, 'fat_formatted');
    }

    public function setProteinFormattedAttribute($value) : void
    {
        $this->attributes['protein'] = (str_replace(',', '.', $value) / 100);
        Arr::forget($this->attributes, 'protein_formatted');
    }
}
