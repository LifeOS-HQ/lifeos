<?php

namespace App\Models\Services\Data\Attributes;

use App\Models\Services\Data\Value;
use App\User;
use Illuminate\Database\Eloquent\Builder;
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
                return $this->kjToKcal($raw);
                break;
            case 'sleep':
            case 'time_in_bed':
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
    }

    public function valueFormatted($value) {

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

    public function getColorAttribute() : string
    {
        return \App\Support\Chart\Color::get($this->id);
    }

    public function getPathAttribute()
    {
        return '/attribute/' . $this->id;
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
