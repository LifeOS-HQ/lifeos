<?php

namespace App\Models\Journals;

use App\Models\Journals\Gratitude\Gratitude;
use App\Models\Journals\Rating;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Journal extends Model
{
    protected $appends = [
        'path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'date',
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
            $model->name = 'Eintrag vom ' . $model->date->format('d.m.Y');

            return true;
        });

        static::created(function($model)
        {
            $lastJournal = self::with(['ratings'])
                ->where('id', '!=', $model->id)
                ->latest()
                ->first();

            if (is_null($lastJournal)) {
                return true;
            }

            foreach ($lastJournal->ratings as $rating) {
                $model->ratings()->create([
                    'journal_id' => $lastJournal->id,
                    'user_id' => $lastJournal->user_id,
                    'title' => $rating->title,
                    'order_column' => $rating->order_column,
                ]);
            }

        });

        static::deleting(function($model)
        {
            $model->gratitudes()->delete();
            $model->ratings()->delete();

            return true;
        });
    }

    public function getPathAttribute()
    {
        return '/journal/' . $this->id;
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function gratitudes() : HasMany
    {
        return $this->hasMany(Gratitude::class, 'journal_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ratings() : HasMany
    {
        return $this->hasMany(Rating::class, 'journal_id');
    }

}
