<?php

namespace App\Models\Reviews;

use App\Support\Markdown;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $appends = [
        'body_markdown',
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

    protected $table = 'block_review';

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
        return '/review/' . $this->review_id . '/block/' . $this->id;
    }

    public function getBodyMarkdownAttribute() : string
    {
        if (is_null($this->body)) {
            return '';
        }

        return Markdown::convertToHtml($this->body);
    }
}
