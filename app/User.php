<?php

namespace App;

use App\Models\Activities\Activity;
use App\Models\Contacts\Contact;
use App\Models\Exercises\Exercise;
use App\Models\Journals\Journal;
use App\Models\Lifeareas\Lifearea;
use App\Models\Places\Place;
use App\Models\Reviews\Review;
use App\Models\Services\Service;
use App\Models\Websites\Website;
use App\Models\Work\Month;
use App\Models\Work\Time;
use App\Models\Work\Year;
use App\Models\Workouts\Workout;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function activities() : HasMany
    {
        return $this->hasMany(Activity::class, 'user_id');
    }

    public function clients() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_client', 'coach_id', 'client_id');
    }

    public function coaches() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_client', 'client_id', 'coach_id');
    }

    public function contacts() : HasMany
    {
        return $this->hasMany(Contact::class, 'user_id');
    }

    public function diet_days() : HasMany
    {
        return $this->hasMany(\App\Models\Diet\Diary\Day::class, 'user_id');
    }

    public function diet_meals() : HasMany
    {
        return $this->hasMany(\App\Models\Diet\Meals\Meal::class, 'user_id');
    }

    public function diet_plans() : HasMany
    {
        return $this->hasMany(\App\Models\Diet\Plans\Plan::class, 'user_id');
    }

    public function exercises() : HasMany
    {
        return $this->hasMany(Exercise::class, 'user_id');
    }

    public function journals() : HasMany
    {
        return $this->hasMany(Journal::class, 'user_id');
    }

    public function lifeareas() : HasMany
    {
        return $this->hasMany(Lifearea::class, 'user_id');
    }

    public function places() : HasMany
    {
        return $this->hasMany(Place::class, 'user_id');
    }

    public function reviews() : HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function services() : BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_user')->withPivot([
            'id',
            'token',
            'token_secret',
            'refresh_token',
            'username',
            'password',
            'expires_at',
        ])->withTimestamps();
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

    public function websites() : HasMany
    {
        return $this->hasMany(Website::class, 'user_id');
    }

    public function workouts() : HasMany
    {
        return $this->hasMany(Workout::class, 'user_id');
    }
}
