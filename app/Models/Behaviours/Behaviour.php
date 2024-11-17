<?php

namespace App\Models\Behaviours;

use App\Models\Behaviours\Attributes\Attribute;
use App\User;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Behaviour extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'behaviours';

    protected $appends = [
        'attributes_path',
        'histories_path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'habitica_uuid',
        'name',
        'source_id',
        'source_slug',
        'user_id',
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
    }

    public function isDeletable() : bool
    {
        return true;
    }

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Verhalten',
                'plural' => 'Verhalten',
            ],
        ];
    }

    public function getAttributesPathAttribute(): string
    {
        return route('behaviours.attributes.index', [
            'behaviour' => $this->id,
        ]);
    }

    public function getHistoriesPathAttribute(): string
    {
        return route('behaviours.histories.index', ['behaviour' => $this->id]);
    }

    public function dataAttributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
