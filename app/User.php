<?php

namespace App;

use App\Models\Journals\Journal;
use App\Models\Lifeareas\Lifearea;
use App\Models\Reviews\Review;
use App\Models\Work\Month;
use App\Models\Work\Time;
use App\Models\Work\Year;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function journals() : HasMany
    {
        return $this->hasMany(Journal::class, 'user_id');
    }

    public function lifeareas() : HasMany
    {
        return $this->hasMany(Lifearea::class, 'user_id');
    }

    public function reviews() : HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function working_years() : HasMany
    {
        return $this->hasMany(Year::class);
    }

    public function working_months() : HasMany
    {
        return $this->hasMany(Month::class);
    }

    public function working_times() : HasMany
    {
        return $this->hasMany(Time::class);
    }
}
