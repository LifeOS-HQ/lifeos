<?php

namespace App\Models\Behaviours;

use App\User;
use App\Models\Behaviours\Behaviour;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Behaviours\Histories\Attributes\Value;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class History extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'behaviours.histories';

    protected $appends = [
        'end_at_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'start_at',
        'end_at',
    ];

    protected $fillable = [
        'behaviour_id',
        'user_id',
        'start_at',
        'end_at',
        'comment',
    ];

    protected $table = 'behaviours_histories';

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
                'singular' => 'Verlauf',
                'plural' => 'Verläufe',
            ],
        ];
    }

    public function getEndAtFormattedAttribute() : string
    {
        return $this->end_at->format('d.m.Y H:i');
    }

    public function getStartAtFormattedAttribute() : string
    {
        return $this->end_at->format('d.m.Y H:i');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'behaviour' => $this->behaviour_id,
            'history' => $this->id,
        ];
    }

    public function behaviour(): BelongsTo
    {
        return $this->belongsTo(Behaviour::class, 'behaviour_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(Value::class, 'history_id', 'id');
    }
}
