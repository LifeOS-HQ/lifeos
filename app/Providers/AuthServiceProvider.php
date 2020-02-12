<?php

namespace App\Providers;

use App\Models\Journals\Gratitude\Gratitude;
use App\Models\Journals\Journal;
use App\Models\Lifeareas\Lifearea;
use App\Models\Reviews\Block;
use App\Models\Reviews\Lifearea as ReviewLifearea;
use App\Models\Reviews\Review;
use App\Models\Work\Month;
use App\Policies\Journals\GratitudePolicy;
use App\Policies\Journals\JournalPolicy;
use App\Policies\Lifeareas\LifeareaPolicy;
use App\Policies\Reviews\BlockPolicy;
use App\Policies\Reviews\LifeareaPolicy as ReviewLifeareaPolicy;
use App\Policies\Reviews\ReviewPolicy;
use App\Policies\Work\MonthPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Journal::class => JournalPolicy::class,
        Month::class => MonthPolicy::class,
        Gratitude::class => GratitudePolicy::class,
        Review::class => ReviewPolicy::class,
        Block::class => BlockPolicy::class,
        Lifearea::class => LifeareaPolicy::class,
        ReviewLifearea::class => ReviewLifeareaPolicy::class,
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
