<?php

namespace App\Models\Services;

use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Pivot
{
    use HasFactory;

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'expires_at',
    ];

    protected $guarded = [
        'id',
    ];

    public $table = 'service_user';

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

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
}
