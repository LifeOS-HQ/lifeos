<?php

namespace App\Models\Behaviours\Histories\Attributes;

use Illuminate\Support\Arr;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use App\Models\Services\Data\Attributes\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Value extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'behaviours.histories.values';

    protected $appends = [
        'number_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'user_id',
        'history_id',
        'attribute_id',
        'raw',
        'number_formatted',
    ];

    protected $table = 'behaviours_histories_attribute_values';

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

    public function isDeletable(): bool
    {
        return true;
    }

    protected static function labels(): array
    {
        return [
            'nominativ' => [
                'singular' => 'Wert',
                'plural' => 'Werte',
            ],
        ];
    }

    public function getRouteParameterAttribute(): array
    {
        return [
            'history' => $this->history_id,
            'value' => $this->id,
        ];
    }

    public function getNumberFormattedAttribute(): string
    {
        return number_format($this->raw, 2, ',', '.');
    }

    public function setNumberFormattedAttribute($value): void
    {
        $this->attributes['raw'] = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'number_formatted');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }
}
