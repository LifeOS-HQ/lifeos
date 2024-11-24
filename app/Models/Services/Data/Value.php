<?php

namespace App\Models\Services\Data;

use Illuminate\Database\Eloquent\Model;
use App\Models\Services\Data\Attributes\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Value extends Model
{
    protected $appends = [
        'path',
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

    public function getPathAttribute()
    {
        return '/value/' . $this->id;
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
}
