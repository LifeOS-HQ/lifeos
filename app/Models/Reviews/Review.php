<?php

namespace App\Models\Reviews;

use App\Models\Reviews\Block;
use App\Models\Reviews\Lifearea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class Review extends Model
{
    protected $appends = [
        'path',
        'at_formatted'
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'at',
    ];

    protected $guarded = [
        'id',
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
            $lifeareas = \App\Models\Lifeareas\Lifearea::where('user_id', $model->user_id)->orderBy('title', 'ASC')->get();
            foreach ($lifeareas as $key => $lifearea) {
                $model->lifeareas()->create([
                    'user_id' => $model->user_id,
                    'lifearea_id' => $lifearea->id,
                ]);
            }

            $lastModel = self::with([
                'blocks'
            ])
                ->where('id', '!=', $model->id)
                ->latest()
                ->first();

            if (is_null($lastModel) || count($lastModel->blocks) == 0) {
                $model->blocks()->create([
                    'title' => 'Bewertung',
                    'user_id' => $model->user_id,
                ]);
                return true;
            }

            foreach ($lastModel->blocks as $block) {
                $model->blocks()->create([
                    'title' => $block->title,
                    'order_column' => $block->order_column,
                    'user_id' => $model->user_id,
                ]);
            }


        });

        static::updating(function($model)
        {
            return true;
        });
    }

    public function getPathAttribute()
    {
        return '/review/' . $this->id;
    }

    public function getAtFormattedAttribute() : string
    {
        return $this->at->format('d.m.Y');
    }

    public function setAtFormattedAttribute($value)
    {
        $this->attributes['at'] = Carbon::createFromFormat('d.m.Y', $value)->setTime(0, 0, 0);
        Arr::forget($this->attributes, 'at_formatted');
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function blocks() : HasMany
    {
        return $this->hasMany(Block::class, 'review_id');
    }

    public function lifeareas() : HasMany
    {
        return $this->hasMany(Lifearea::class, 'review_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
