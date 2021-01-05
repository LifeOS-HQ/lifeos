<?php

namespace App\Models\Services\Data\Attributes;

use App\Models\Services\Data\Value;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    protected $appends = [
        'path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $guarded = [
        'id',
    ];

    public $table = 'data_attributes';

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

    public function value($raw)
    {
        if (is_null($raw)) {
            return null;
        }

        switch ($this->slug) {
            case 'active_energy':
            case 'energy':
                return round($raw / 0.004184 / 1000, 0);
                break;

            default:
                return $raw;
                break;
        }
    }

    public function getPathAttribute()
    {
        return '/attribute/' . $this->id;
    }

    public function values() : HasMany
    {
        return $this->hasMany(Value::class, 'attribute_id');
    }
}
