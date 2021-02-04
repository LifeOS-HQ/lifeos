<?php

namespace App\Traits;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}