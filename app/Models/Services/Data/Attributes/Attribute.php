<?php

namespace App\Models\Services\Data\Attributes;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Services\Data\Value;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Services\Data\Attributes\Groups\Group;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends Model
{
    use HasFactory;

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
     * Type of the attribute
     * is responsible for chart options and value formatting
     * @var [type]
     */
    protected $attribute_type;

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

        static::retrieved(function($model)
        {
            return true;
        });
    }

    protected function setAttributeType()
    {
        $this->attribute_type = \App\Models\Services\Data\Attributes\Types\Factory::make($this->slug, $this->raw);
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

        // return $this->attribute_type->value($raw);

        switch ($this->slug) {
            case 'active_energy':
            case 'energy':
                return $this->kjToKcal($raw);
                break;
            case 'body_fat':
                return $this->toPercentage($raw);
            case 'leisure_min':
            case 'sleep':
            case 'time_in_bed':
            case 'working_min':
            case 'workouts_min':
                return $this->minToHour($raw);
                break;
            case 'sleep_start':
                return now()->hour(12)->minute(0)->second(0)->addMinutes($raw)->format('H:i');
                break;
            case 'sleep_end':
                return now()->hour(0)->minute(0)->second(0)->addMinutes($raw)->format('H:i');
                break;
            default:
                return (float) $raw;
                break;
        }

        return $this->attribute_type->formatted($raw);
    }

    public function getBgClass($raw) : string
    {
        if ($this->slug != 'mood') {
            return '';
        }

        switch ($raw) {
            case 1: return 'bg-mood-1';
            case 2: return 'bg-mood-2';
            case 3: return 'bg-mood-3';
            case 4: return 'bg-mood-4';
            case 5: return 'bg-mood-5';
            case 6: return 'bg-mood-6';
            case 7: return 'bg-mood-7';
            case 8: return 'bg-mood-8';
            case 9: return 'bg-mood-9';

            default: return 'bg-dark';
        }
    }

    protected function kjToKcal($raw) : int
    {
        return round($raw / 0.004184 / 1000, 0);
    }

    protected function minToHour($raw) : float
    {
        return round($raw / 60, 2);
    }

    protected function toPercentage($raw) : float
    {
        return round($raw * 100, 2);
    }

    public function getColorAttribute() : string
    {
        return \App\Support\Chart\Color::get($this->id);
    }

    public function getAxisOptions() : array
    {
        $options = [
            'title' => [
                'text' => $this->attribute_type->label . ' (' . $this->attribute_type->unit . ')',
            ],
        ];

        return $options;
    }

    public function getAttributeTypeAttribute()
    {
        return $this->attribute_type;
    }

    public function getPathAttribute()
    {
        return '/attribute/' . $this->id;
    }

    public function setSlugAttribute($value) : void
    {
        $this->attributes['slug'] = $value;
        $this->setAttributeType();
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function values() : HasMany
    {
        return $this->hasMany(Value::class, 'attribute_id');
    }

    public function loadValuesForUser(User $user, int $values_count) : self
    {
        $this->load([
            'values' => function ($query) use ($user, $values_count){
                return $query->where('user_id', $user->id)
                    ->latest('at')
                    ->take($values_count);
            },
        ]);

        return $this;
    }

    public function scopeForUser(Builder $query, User $user) : Builder
    {
        return $query->where('user_id', $user->id);
    }
}
