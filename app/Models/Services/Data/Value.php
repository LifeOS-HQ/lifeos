<?php

namespace App\Models\Services\Data;

use App\Models\Days\Day;
use Illuminate\Database\Eloquent\Model;
use App\Models\Services\Data\Attributes\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Value extends Model
{
    protected $appends = [
        'path',
        'formatted_value',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'at',
    ];

    protected $guarded = [
        'id',
    ];

    public $table = 'data_values';

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
            if (empty($model->day_id)) {
                $model->setDayId();
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

    public function isDeletable() : bool
    {
        return true;
    }

    public function setDayId(): self
    {
        $this->day_id = Day::firstOrCreate([
            'user_id' => $this->user_id,
            'date' => $this->at->format('Y-m-d'),
        ])->id;

        return $this;
    }

    public function getFormattedValueAttribute()
    {
        return $this->attribute->value($this->raw);
    }

    public function getPathAttribute()
    {
        return '/value/' . $this->id;
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
}
