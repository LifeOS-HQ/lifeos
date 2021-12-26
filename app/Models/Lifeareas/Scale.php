<?php

namespace App\Models\Lifeareas;

use App\Models\Lifeareas\Levels\Goals\Goal;
use App\Models\Lifeareas\Lifearea;
use App\Traits\BelongsToUser;
use App\User;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scale extends Model
{
    use BelongsToUser,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'lifearea.scale';

    protected $appends = [
        'is_deletable',
        'path',
        'show_path',
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

    protected $table = 'lifearea_scale';

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

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Level',
                'plural' => 'Level',
            ],
        ];
    }

    public function isDeletable() : bool
    {
        return false;
    }

    public function getIsDeletableAttribute() : bool
    {
        return $this->isDeletable();
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'lifearea' => $this->lifearea_id,
            'scale' => $this->id,
        ];
    }

    public function getShowPathAttribute() : string
    {
        return route($this->base_route . '.show', [
            'lifearea' => $this->lifearea_id,
            'scale' => $this->value,
        ]);
    }

    public function lifearea() : BelongsTo
    {
        return $this->belongsTo(Lifearea::class, 'lifearea_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function goals() : HasMany
    {
        return $this->hasMany(Goal::class, 'level_id');
    }
}
