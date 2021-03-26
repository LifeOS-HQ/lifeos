<?php

namespace App\Models\Blog\Posts;

use App\Support\Markdown;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'blog.posts';

    protected $appends = [
        'body_markdown',
        'published_at_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'published_at',
    ];

    protected $fillable = [
        'author_id',
        'title',
        'body',
        'published_at',
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
                'singular' => 'Post',
                'plural' => 'Posts',
            ],
        ];
    }

    public function setTitleAttribute(string $value) : void
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value, '-', 'de');
    }

    public function getBodyMarkdownAttribute() : string
    {
        if (is_null($this->body)) {
            return '';
        }

        return Markdown::convertToHtml($this->body);
    }

    public function getPublishedAtFormattedAttribute() : string
    {
        if (is_null($this->published_at)) {
            return '-';
        }

        return $this->published_at->format('d.m.Y H:i');
    }

    public function getPublishPathAttribute() : string
    {
        return route('blog.posts.publish.store', [
            'post' => $this->id,
        ]);
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'post' => $this->id,
        ];
    }
}
