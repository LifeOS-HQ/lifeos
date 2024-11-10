<?php

namespace App\Models\Behaviours\Attributes;

use App\User;
use Illuminate\Support\Arr;
use App\Models\Behaviours\Behaviour;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Services\Data\Attributes\Attribute as DataAttribute;

class Attribute extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'behaviours.attributes';

    protected $appends = [
        'path',
        'default_number_formatted',
        'goal_number_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'attribute_id',
        'behaviour_id',
        'default_value',
        'goal_value',
        'service_slug',
        'user_id',
        'default_number_formatted',
        'goal_number_formatted',
    ];

    protected $table = 'behaviours_attributes';

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
                'singular' => 'Attribut',
                'plural' => 'Attribute',
            ],
        ];
    }

    public function getRouteParameterAttribute(): array
    {
        return [
            'behaviour' => $this->behaviour_id,
            'attribute' => $this->id,
        ];
    }

    public function getDefaultNumberFormattedAttribute(): string
    {
        return number_format($this->default_value, 2, ',', '.');
    }

    public function setDefaultNumberFormattedAttribute($value): void
    {
        $this->attributes['default_value'] = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'default_number_formatted');
    }

    public function getGoalNumberFormattedAttribute(): string
    {
        return number_format($this->goal_value, 2, ',', '.');
    }

    public function setGoalNumberFormattedAttribute($value): void
    {
        $this->attributes['goal_value'] = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'goal_number_formatted');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(DataAttribute::class, 'attribute_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function behaviour(): BelongsTo
    {
        return $this->belongsTo(Behaviour::class, 'behaviour_id');
    }
}
