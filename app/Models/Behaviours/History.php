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
        'end_at_time_formatted',
        'start_at_formatted',
        'value_path',
        'audio_path',
        'commit_path',
        'complete_path',
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
        'comment',
        'day_id',
        'end_at_formatted',
        'end_at',
        'is_committed',
        'is_completed',
        'source_id',
        'source_slug',
        'start_at_formatted',
        'start_at',
        'user_id',
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
                        'attribute_id' => $attribute->attribute_id,
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

    public function cache() : void
    {
        $this->loadMissing([
            'values.attribute',
            'day',
        ]);

        if ($this->values->count() > 0) {
            $attribute_slugs = $this->values->pluck('attribute.slug')->toArray();
            $this->day->calculateAttributeValues($attribute_slugs);
        }
    }

    public function getEndAtFormattedAttribute() : string
    {
        return $this->end_at->setTimezone('Europe/Berlin')->format('d.m.Y H:i');
    }

    public function getEndAtTimeFormattedAttribute() : string
    {
        return $this->end_at->setTimezone('Europe/Berlin')->format('H:i');
    }

    public function setEndAtFormattedAttribute($value) : void
    {
        $this->attributes['end_at'] = \Carbon\Carbon::createFromFormat('d.m.Y H:i', $value, 'Europe/Berlin');
        Arr::forget($this->attributes, 'end_at_formatted');
    }

    public function getStartAtFormattedAttribute() : string
    {
        return $this->start_at->setTimezone('Europe/Berlin')->format('d.m.Y H:i');
    }

    public function setStartAtFormattedAttribute($value) : void
    {
        $this->attributes['start_at'] = \Carbon\Carbon::createFromFormat('d.m.Y H:i', $value, 'Europe/Berlin');
        Arr::forget($this->attributes, 'start_at_formatted');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'behaviour' => $this->behaviour_id,
            'history' => $this->id,
        ];
    }

    public function getCommitPathAttribute(): string
    {
        return route('behaviours.histories.commit.store', [
            'history' => $this->id,
        ]);
    }

    public function getCompletePathAttribute(): string
    {
        return route('behaviours.histories.complete.store', [
            'history' => $this->id,
        ]);
    }

    public function getAudioPathAttribute(): string
    {
        return resource_path('audio/daily.mp3');
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
