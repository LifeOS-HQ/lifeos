<?php

namespace App\Providers;

use App\Models\Work\Month;
use App\Models\Reviews\Block;
use App\Models\Reviews\Review;
use App\Models\Journals\Rating;
use App\Models\Lifeareas\Scale;
use App\Models\Journals\Journal;
use App\Policies\ObstaclePolicy;
use App\Models\Lifeareas\Lifearea;
use App\Models\Obstacles\Obstacle;
use App\Policies\Work\MonthPolicy;
use App\Models\Activities\Activity;
use Illuminate\Support\Facades\Gate;
use App\Policies\Reviews\BlockPolicy;
use App\Policies\Reviews\ReviewPolicy;
use App\Policies\Journals\RatingPolicy;
use App\Policies\Lifeareas\ScalePolicy;
use App\Policies\Journals\JournalPolicy;
use App\Policies\Journals\GratitudePolicy;
use App\Policies\Lifeareas\LifeareaPolicy;
use App\Policies\Activities\ActivityPolicy;
use App\Models\Journals\Gratitude\Gratitude;
use App\Models\Reviews\Lifearea as ReviewLifearea;
use App\Policies\Reviews\LifeareaPolicy as ReviewLifeareaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\Behaviours\Attributes\Attribute::class => \App\Policies\Behaviours\Attributes\AttributePolicy::class,
        \App\Models\Behaviours\Behaviour::class => \App\Policies\Behaviours\BehaviourPolicy::class,
        \App\Models\Behaviours\Histories\Attributes\Value::class => \App\Policies\Behaviours\Histories\Attributes\ValuePolicy::class,
        \App\Models\Behaviours\History::class => \App\Policies\Behaviours\HistoryPolicy::class,
        \App\Models\Contacts\Contact::class => \App\Policies\Contacts\ContactPolicy::class,
        \App\Models\Days\Day::class => \App\Policies\Days\DayPolicy::class,
        \App\Models\Diet\Diary\Day::class => \App\Policies\Diet\Diary\DayPolicy::class,
        \App\Models\Diet\Diary\Meals\Meal::class => \App\Policies\Diet\Diary\Meals\MealPolicy::class,
        \App\Models\Diet\Meals\Meal::class => \App\Policies\Diet\Meals\MealPolicy::class,
        \App\Models\Diet\Plans\Day::class => \App\Policies\Diet\Plans\DayPolicy::class,
        \App\Models\Diet\Plans\Meals\Meal::class => \App\Policies\Diet\Plans\Meals\MealPolicy::class,
        \App\Models\Diet\Plans\Plan::class => \App\Policies\Diet\Plans\PlanPolicy::class,
        \App\Models\Exercises\Exercise::class => \App\Policies\Exercises\ExercisePolicy::class,
        \App\Models\Journals\Activities\Activity::class => \App\Policies\Journals\ActivityPolicy::class,
        \App\Models\Services\Service::class => \App\Policies\Services\ServicePolicy::class,
        \App\Models\Widgets\Users\User::class => \App\Policies\Widgets\Users\UserPolicy::class,
        \App\Models\Workouts\History::class => \App\Policies\Behaviours\HistoryPolicy::class,
        \App\Models\Workouts\Workout::class => \App\Policies\Workouts\WorkoutPolicy::class,
        Activity::class => ActivityPolicy::class,
        Block::class => BlockPolicy::class,
        Gratitude::class => GratitudePolicy::class,
        Journal::class => JournalPolicy::class,
        Lifearea::class => LifeareaPolicy::class,
        Month::class => MonthPolicy::class,
        Rating::class => RatingPolicy::class,
        Review::class => ReviewPolicy::class,
        ReviewLifearea::class => ReviewLifeareaPolicy::class,
        Scale::class => ScalePolicy::class,
        Obstacle::class => ObstaclePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
