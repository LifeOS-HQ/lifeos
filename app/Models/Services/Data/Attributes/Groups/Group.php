<?php

namespace App\Models\Services\Data\Attributes\Groups;

use App\Models\Services\Data\Attributes\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
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

    public $table = 'data_attributes_groups';

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
        return '/group/' . $this->id;
    }

    public function attributes() : HasMany
    {
        return $this->hasMany(Attribute::class, 'group_id')->orderBy('name', 'ASC');
    }

    public function scopeWithoutCustom(Builder $query): Builder
    {
        return $query->where('id', '!=', 18);
    }
}
