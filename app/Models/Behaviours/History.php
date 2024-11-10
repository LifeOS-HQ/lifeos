<?php

namespace App\Models\Behaviours;

use App\User;
use App\Models\Days\Day;
use Illuminate\Support\Arr;
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
        'value_path',
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
        'end_at_formatted',
        'day_id',
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
            if (empty($model->start_at)) {
                $model->start_at = $model->end_at;
            }

            if (empty($model->day_id)) {
                $model->day_id = Day::firstOrCreate([
                    'user_id' => $model->user_id,
                    'date' => $model->start_at->format('Y-m-d'),
                ])->id;
            }

            return true;
        });

        static::created(function($model)
        {
            $model->loadMissing([
                'behaviour.dataAttributes',
            ]);
            if ($model->behaviour->dataAttributes->count() > 0) {
                foreach ($model->behaviour->dataAttributes as $attribute) {
                    $model->values()->create([
                        'user_id' => $model->user_id,
                        'attribute_id' => $attribute->id,
                        'number_formatted' => $attribute->default_number_formatted,
                    ]);
                }
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
                'singular' => 'Verlauf',
                'plural' => 'Verläufe',
            ],
        ];
    }

    public function getEndAtFormattedAttribute() : string
    {
        return $this->end_at->format('d.m.Y H:i');
    }

    public function setEndAtFormattedAttribute($value) : void
    {
        $this->attributes['end_at'] = \Carbon\Carbon::createFromFormat('d.m.Y H:i', $value);
        Arr::forget($this->attributes, 'end_at_formatted');
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

    public function getValuePathAttribute(): string
    {
        return route('behaviours.histories.values.index', [
            'history' => $this->id,
        ]);
    }

    public function behaviour(): BelongsTo
    {
        return $this->belongsTo(Behaviour::class, 'behaviour_id', 'id');
    }

    public function day(): BelongsTo
    {
        return $this->belongsTo(Day::class, 'day_id', 'id');
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
