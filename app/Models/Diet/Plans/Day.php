<?php

namespace App\Models\Diet\Plans;

use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'diet.plans.days';

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
        'user_id',
        'plan_id',
        'name',
        'order_by',
    ];

    protected $table = 'diet_plans_days';

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
            $model->setDefaultName();

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

    public function setDefaultName() : void
    {
        $week_count = ceil($this->order_by / 7);
        $day_count = ($this->order_by % 7) ?: 7;

        $this->name = 'Woche ' . $week_count . ', Tag ' . $day_count;
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'plan' => $this->plan_id,
            'day' => $this->id
        ];
    }
}
