<?php

namespace App\Models\Days;

use App\Apis\Exist\Http;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Models\Services\Service;
use App\Models\Behaviours\History;
use App\Models\Services\Data\Value;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Artisan;

class Day extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'days';

    protected $appends = [
        'date_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'date',
    ];

    protected $fillable = [
        'user_id',
        'date',
        'date_formatted',
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

    public function calculateAttributeValues(array $attribute_slugs = []): self
    {
        $query = $this->behaviourHistories()
            ->with('values.attribute')
            ->whereHas('values', function($query) use ($attribute_slugs) {
                if (empty($attribute_slugs)) {
                    return;
                }

                $query->whereHas('attribute', function($query) use ($attribute_slugs) {
                    $query->whereIn('slug', $attribute_slugs);
                });
            })
            ->where('is_completed', true)
            ->where('is_committed', true);

        $histories = $query->get();

        if ($histories->isEmpty()) {
            return $this;
        }

        $attribute_values = [];
        foreach ($histories as $history) {
            foreach ($history->values as $value) {
                if (!isset($attribute_values[$value->attribute->id])) {
                    $attribute_values[$value->attribute->id] = [
                        'slug' => $value->attribute->slug,
                        'date' => $this->date->format('Y-m-d 00:00:00'),
                        'value' => 0,
                    ];
                }

                $attribute_values[$value->attribute->id]['value'] += $value->raw;
            }
        }

        $service = Service::query()
            ->where('slug', 'exist')
            ->first();

        $attribute_ids = [];
        foreach ($attribute_values as $attribute_id => $attribute_value) {
            $value = Value::updateOrCreate([
                'user_id' => $this->user_id,
                'attribute_id' => $attribute_id,
                'service_id' => $service->id,
                'at' => $attribute_value['date'],
            ], [
                'raw' => $attribute_value['value'],
            ]);

            if (in_array($attribute_value['slug'], Http::PROVIDED_ATTRIBUTES) && ($value->wasRecentlyCreated || ! empty($value->getChanges()))) {
                $attribute_ids[] = $attribute_id;
            }
        }

        if (empty($attribute_ids)) {
            return $this;
        }

        $service_user = \App\Models\Services\User::query()
            ->where('user_id', $this->user_id)
            ->where('service_id', $service->id)
            ->first();

        if ($service_user) {
            Artisan::queue('services:exist:api:attributes:update', [
                'day' => $this->id,
                '--attribute' => $attribute_ids,
            ]);
        }

        return $this;
    }

    public function isDeletable() : bool
    {
        return true;
    }

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Tag',
                'plural' => 'Tage',
            ],
        ];
    }

    public function getDateFormattedAttribute() : string
    {
        return $this->date->format('d.m.Y');
    }

    public function setDateFormattedAttribute($value) : void
    {
        $this->attributes['date'] = Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
        Arr::forget($this->attributes, 'date_formatted');
    }

    public function behaviourHistories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(Value::class);
    }
}
