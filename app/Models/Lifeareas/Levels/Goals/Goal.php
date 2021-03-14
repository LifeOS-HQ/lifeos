<?php

namespace App\Models\Lifeareas\Levels\Goals;

use App\Models\Lifeareas\Scale;
use App\Models\Services\Data\Attributes\Attribute;
use App\Traits\BelongsToUser;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class Goal extends Model
{
    use BelongsToUser,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'lifeareas.levels.goals';

    protected $appends = [
        'end_formatted',
        'should_edit',
        'start_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'user_id',
        'lifearea_id',
        'level_id',
        'data_attribute_id',
        'start',
        'start_formatted',
        'end',
        'end_formatted',
    ];

    public $table = 'lifearea_level_goal';

    protected $should_edit = false;

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
            $model->should_edit = true;

            return true;
        });

        static::updating(function($model)
        {
            return true;
        });
    }

    public static function indexPath(array $attributes = []) : string
    {
        return route(self::ROUTE_NAME . '.index', $attributes);
    }

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Ziel',
                'plural' => 'Ziele',
            ],
        ];
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'lifearea' => $this->lifearea_id,
            'goal' => $this->id,
            'level' => $this->level->value,
        ];
    }

    public function getEndFormattedAttribute() : string
    {
        return number_format($this->attributes['end'], 2, ',', '.');
    }

    public function getShouldEditAttribute() : bool
    {
        return $this->should_edit;
    }

    public function setShouldEditAttribute($value) : void
    {
        $this->should_edit = $value;
    }

    public function getStartFormattedAttribute() : string
    {
        return number_format($this->attributes['start'], 2, ',', '.');
    }

    public function setEndFormattedAttribute($value) : void
    {
        $this->attributes['end'] = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'end_formatted');
    }

    public function setStartFormattedAttribute($value) : void
    {
        $this->attributes['start'] = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'start_formatted');
    }

    public function data_attribute() : BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'data_attribute_id');
    }

    public function level() : BelongsTo
    {
        return $this->belongsTo(Scale::class, 'level_id');
    }
}