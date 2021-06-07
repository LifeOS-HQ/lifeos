<?php

namespace App\Models\Diet\Diary;

use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

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
            if (is_null($model->at)) {
                $model->at = now();
            }

            return true;
        });

        static::created(function($model)
        {
            $last_model = self::with([
                'meals'
            ])
                ->where('user_id', $model->user_id)
                ->where('id', '!=', $model->id)
                ->latest()
                ->first();

            if (is_null($last_model) || $last_model->meals->count() == 0) {
                return true;
            }

            foreach ($last_model->meals as $meal) {
                $model->meals()->create([
                    'at' => $meal->at,
                    'name' => $meal->name,
                    'order_by' => $meal->order_by,
                    'user_id' => $model->user_id,
                ]);
            }

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
