<?php

namespace App\Models\Journals\Gratitude;

use App\Models\Journals\Journal;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Gratitude extends Model
{
    protected $appends = [
        'path',
        'edit_path',
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
            if (is_null($model->user_id)) {
                $model->user_id = Auth::user()->id;
            }

            return true;
        });

        static::updating(function($model)
        {
            //
        });
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function journal() : BelongsTo
    {
        return $this->belongsTo(Journal::class, 'journal_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getPathAttribute()
    {
        return $this->path('show');
    }

    public function getEditPathAttribute()
    {
        return $this->path('edit');
    }

    protected function path(string $action = '') : string
    {
        return route($this->baseRoute() . '.' . $action, [
            'journal' => $this->journal_id,
            'gratitude' => $this->id
        ]);
    }

    protected function baseRoute() : string
    {
        return 'journal.gratitude';
    }
}
