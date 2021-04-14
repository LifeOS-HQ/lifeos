<?php

namespace App\Traits;

use App\Models\Comments\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    public function initializeHasComments()
    {
        $this->append('comments_path');
    }

    public function comments() : MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getCommentsPathAttribute() : string
    {
        return route(Comment::ROUTE_NAME . '.index', [
            'type' => static::ROUTE_NAME,
            'model' => $this->id,
        ]);
    }
}