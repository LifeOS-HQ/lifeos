<?php

namespace App\Models\Widgets\Users;

use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'widgets.user';

    const WIDGETS = [
        'widget-health-steps' => 'Schritte',
        'widget-health-weight' => 'Gewicht',
    ];

    protected $appends = [
        //
    ];

    protected $casts = [
        'filters' => 'array',
        'options' => 'array',
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'user_id',
        'widget',
        'view',
        'is_active',
        'column',
        'sort',
        'filters',
        'options'
    ];

    public $table = 'user_widget';

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
                'singular' => 'Widget',
                'plural' => 'Widgets',
            ],
        ];
    }

    protected function getAvailablePaths() : array
    {
        return [
            'edit_path',
            'index_path',
            'path',
        ];
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'view' => $this->view,
            'user' => $this->id
        ];
    }
}
