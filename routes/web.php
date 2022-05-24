<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::bind('model', function ($id) {
    switch(app()->request->route('type')) {
        case \App\Models\Contacts\Contact::ROUTE_NAME: return \App\Models\Contacts\Contact::findOrFail($id); break;

        default: abort(404);
    }
});

Route::get('/', function () {
    return view('marketing.landing');
});

Route::post('deploy', 'DeploymentController@store');

Auth::routes();

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('/blog/posts', '\App\Http\Controllers\Blog\Posts\PostController', ['as' => 'blog']);
    Route::post('/blog/posts/{post}/publish', [\App\Http\Controllers\Blog\Posts\PublishController::class, 'store'])->name('blog.posts.publish.store');
});

Route::get('/impressum', [\App\Http\Controllers\ImpressumController::class, 'index'])->name('impressum.index');
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

Route::get('/blog', [\App\Http\Controllers\Blog\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [\App\Http\Controllers\Blog\BlogController::class, 'show'])->name('blog.show');

Route::middleware(['auth'])->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home/work', 'Home\Work\WorkController@show')->name('home.work.show');
    Route::get('/home/rentablo', 'Home\Rentablo\RentabloController@index')->name('home.rentablo.index');
    Route::get('/home/server', 'Home\Servers\StatusController@index')->name('home.server.index');

    Route::resource(\App\Models\Contacts\Contact::ROUTE_NAME, 'Contacts\ContactController');

    Route::resource('/clients', 'Users\ClientController');

    Route::get('/data/day/{date_string?}', [\App\Http\Controllers\Data\DayController::class, 'index'])->name('data.day.index');
    Route::get('/data/day/{date_string}/{group}', [\App\Http\Controllers\Data\DayController::class, 'show'])->name('data.day.show');

    Route::get('/diet', 'Diet\DietController@index')->name('diet.index');
    Route::resource('/diet/days', 'Diet\Diary\DayController', ['as' => 'diet']);
    Route::resource('/diet/days.meals', 'Diet\Diary\Meals\MealController', ['as' => 'diet']);
    Route::resource('/diet/days/meals.foods', 'Diet\Diary\Meals\FoodController', ['as' => 'diet.days']);

    Route::resource('/diet/foods', 'Diet\Foods\FoodController', ['as' => 'diet']);
    Route::resource('/diet/foods.packagings', 'Diet\Foods\PackagingController', ['as' => 'diet']);

    Route::resource('/diet/meals', 'Diet\Meals\MealController', ['as' => 'diet']);
    Route::resource('/diet/meals.foods', 'Diet\Meals\FoodController', ['as' => 'diet']);

    Route::resource('/diet/plans', 'Diet\Plans\PlanController', ['as' => 'diet']);
    Route::resource('/diet/plans.days', 'Diet\Plans\DayController', ['as' => 'diet']);
    Route::resource('/diet/plans.days.meals', 'Diet\Plans\Meals\MealController', ['as' => 'diet']);
    Route::resource('/diet/plans/meals.foods', 'Diet\Plans\Meals\FoodController', ['as' => 'diet.plans']);

    Route::get('/finance', 'Finance\FinanceController@index')->name('finance.index');
        Route::get('/finance/independence', 'Finance\Independence\IndependenceController@index')->name('finance.independence.index');
    Route::get('/fitness', 'Fitness\FitnessController@index')->name('fitness.index');

    Route::resource(\App\Models\Activities\Activity::ROUTE_NAME, 'Activities\ActivityController');
    Route::resource('health', 'Health\HealthController');

    Route::resource('journal', 'Journals\JournalController');
    Route::resource('journal.activity', 'Journals\Activities\ActivityController');
    Route::resource('journal.gratitude', 'Journals\Gratitude\GratitudeController');
    Route::put('/journal/{journal}/sort/gratitude', 'Journals\Gratitude\SortController@update')->name('journal.sort.gratitude.update');
    Route::put('/journal/{journal}/gratitude/{gratitude}/gamechanger', 'Journals\Gratitude\GameChangerController@update')->name('journal.gratitude.gamechanger.update');
    Route::resource('journal.rating', 'Journals\RatingController');
    Route::put('/journal/{journal}/sort/rating', 'Journals\Ratings\SortController@update')->name('journal.sort.rating.update');

    Route::resource(\App\Models\Lifeareas\Lifearea::ROUTE_NAME, 'Lifeareas\LifeareaController');
    Route::resource(\App\Models\Lifeareas\Scale::ROUTE_NAME, 'Lifeareas\ScaleController');
    Route::get('lifeareas/{lifearea}/levels/{level}/goals', [\App\Http\Controllers\Lifeareas\Levels\Goals\GoalController::class, 'index'])->name('lifeareas.levels.goals.index');
    Route::get('lifeareas/{lifearea}/levels/{level}/goals/create', [\App\Http\Controllers\Lifeareas\Levels\Goals\GoalController::class, 'create'])->name('lifeareas.levels.goals.create');
    Route::post('lifeareas/{lifearea}/levels/{level}/goals', [\App\Http\Controllers\Lifeareas\Levels\Goals\GoalController::class, 'store'])->name('lifeareas.levels.goals.store');
    Route::get('lifeareas/{lifearea}/levels/{level}/goals/{goal}', [\App\Http\Controllers\Lifeareas\Levels\Goals\GoalController::class, 'show'])->name('lifeareas.levels.goals.show');
    Route::get('lifeareas/{lifearea}/levels/{level}/goals/{goal}/edit', [\App\Http\Controllers\Lifeareas\Levels\Goals\GoalController::class, 'edit'])->name('lifeareas.levels.goals.edit');
    Route::put('lifeareas/{lifearea}/levels/{level}/goals/{goal}', [\App\Http\Controllers\Lifeareas\Levels\Goals\GoalController::class, 'update'])->name('lifeareas.levels.goals.update');
    Route::put('lifeareas/{lifearea}/levels/{level}/goals/{goal}', [\App\Http\Controllers\Lifeareas\Levels\Goals\GoalController::class, 'update'])->name('lifeareas.levels.goals.update');
    Route::delete('lifeareas/{lifearea}/levels/{level}/goals/{goal}', [\App\Http\Controllers\Lifeareas\Levels\Goals\GoalController::class, 'destroy'])->name('lifeareas.levels.goals.destroy');

    Route::get('login/{provider}', [App\Http\Controllers\Auth\ServiceController::class, 'redirectToProvider'])->name('login.provider.redirect');
    Route::get('login/{provider}/callback', [App\Http\Controllers\Auth\ServiceController::class, 'handleProviderCallback'])->name('login.provider.callback');

    Route::resource('/portfolio', 'Portfolios\PortfolioController');
    Route::get('/portfolio/dividend/{year}', 'Portfolios\Dividends\MonthController@show');

    Route::resource('review', 'Reviews\ReviewController');
    Route::resource('review.block', 'Reviews\BlockController');
    Route::resource('review.lifearea', 'Reviews\LifeareaController');

    Route::resource('services', 'Services\ServiceController');
    Route::get('user/services', 'Services\UserController@index')->name('user.services.index');
    Route::get('user/services/{service}/create', 'Services\UserController@create')->name('user.services.create');
    Route::post('user/services/{service}', 'Services\UserController@store')->name('user.services.store');
    Route::delete('user/services/{service_user}', 'Services\UserController@destroy')->name('user.services.destroy');

    Route::get('/websites/errorlog', [\App\Http\Controllers\Websites\ErrorlogController::class, 'index'])->name('websites.errorlog.index');
    Route::resource('websites', 'Websites\WebsiteController');

    Route::get('/work', 'Work\WorkController@index')->name('work.index');

    Route::resource('work/time', 'Work\TimeController');
    Route::get('work/month/{year}/{month}', 'Work\Months\ChartController@show');

    Route::resource('work/month', 'Work\MonthController');

    Route::resource('work/year', 'Work\YearController');

    Route::resource('workouts/exercises', 'Exercises\ExerciseController');
    Route::resource('/fitness/workouts', 'Workouts\WorkoutController', ['as' => 'fitness']);
        Route::get('/fitness/workouts/{workout}/exercises', [\App\Http\Controllers\Workouts\Exercises\ExerciseController::class, 'index'])->name('fitness.workouts.exercises.index');
        Route::post('/fitness/workouts/{workout}/exercises', [\App\Http\Controllers\Workouts\Exercises\ExerciseController::class, 'store'])->name('fitness.workouts.exercises.store');
        Route::get('/fitness/workouts/{workout}/exercises/{exercise}', [\App\Http\Controllers\Workouts\Exercises\ExerciseController::class, 'show'])->name('fitness.workouts.exercises.show');
        Route::put('/fitness/workouts/{workout}/exercises/{exercise}', [\App\Http\Controllers\Workouts\Exercises\ExerciseController::class, 'update '])->name('fitness.workouts.exercises.update');
        Route::delete('/fitness/workouts/{workout}/exercises/{exercise}', [\App\Http\Controllers\Workouts\Exercises\ExerciseController::class, 'destroy'])->name('fitness.workouts.exercises.destroy');
            Route::get('/fitness/workouts/{workout}/exercises/{exercise}/sets', [\App\Http\Controllers\Workouts\Exercises\Sets\SetController::class, 'index'])->name('fitness.workouts.exercises.sets.index');
            Route::post('/fitness/workouts/{workout}/exercises/{exercise}/sets', [\App\Http\Controllers\Workouts\Exercises\Sets\SetController::class, 'store'])->name('fitness.workouts.exercises.sets.store');
            Route::get('/fitness/workouts/{workout}/exercises/{exercise}/sets/{set}', [\App\Http\Controllers\Workouts\Exercises\Sets\SetController::class, 'show'])->name('fitness.workouts.exercises.sets.show');
            Route::put('/fitness/workouts/{workout}/exercises/{exercise}/sets/{set}', [\App\Http\Controllers\Workouts\Exercises\Sets\SetController::class, 'update'])->name('fitness.workouts.exercises.sets.update');
            Route::delete('/fitness/workouts/{workout}/exercises/{exercise}/sets/{set}', [\App\Http\Controllers\Workouts\Exercises\Sets\SetController::class, 'destroy'])->name('fitness.workouts.exercises.sets.destroy');
        Route::get('/fitness/workouts/{workout}/histories', [\App\Http\Controllers\Workouts\HistoryController::class, 'index'])->name('fitness.workouts.histories.index');
        Route::post('/fitness/workouts/{workout}/histories', [\App\Http\Controllers\Workouts\HistoryController::class, 'store'])->name('fitness.workouts.histories.store');
        Route::get('/fitness/workouts/{workout}/histories/{history}', [\App\Http\Controllers\Workouts\HistoryController::class, 'show'])->name('fitness.workouts.histories.show');
        Route::put('/fitness/workouts/{workout}/histories/{history}', [\App\Http\Controllers\Workouts\HistoryController::class, 'update '])->name('fitness.workouts.histories.update');
        Route::delete('/fitness/workouts/{workout}/histories/{history}', [\App\Http\Controllers\Workouts\HistoryController::class, 'destroy'])->name('fitness.workouts.histories.destroy');
            Route::get('/fitness/workouts/histories/{history}/exercises', [\App\Http\Controllers\Workouts\Exercises\HistoryController::class, 'index'])->name('fitness.workouts.histories.exercises.index');
            Route::post('/fitness/workouts/histories/{history}/exercises', [\App\Http\Controllers\Workouts\Exercises\HistoryController::class, 'store'])->name('fitness.workouts.histories.exercises.store');
            Route::get('/fitness/workouts/histories/{history}/exercises/{exercise_history}', [\App\Http\Controllers\Workouts\Exercises\HistoryController::class, 'show'])->name('fitness.workouts.histories.exercises.show');
            Route::put('/fitness/workouts/histories/{history}/exercises/{exercise_history}', [\App\Http\Controllers\Workouts\Exercises\HistoryController::class, 'update '])->name('fitness.workouts.histories.exercises.update');
            Route::delete('/fitness/workouts/histories/{history}/exercises/{exercise_history}', [\App\Http\Controllers\Workouts\Exercises\HistoryController::class, 'destroy'])->name('fitness.workouts.histories.exercises.destroy');
                Route::get('/fitness/workouts/histories/{history}/exercises/{exercise_history}/sets', [\App\Http\Controllers\Workouts\Exercises\Sets\HistoryController::class, 'index'])->name('fitness.workouts.histories.exercises.sets.index');
                Route::post('/fitness/workouts/histories/{history}/exercises/{exercise_history}/sets', [\App\Http\Controllers\Workouts\Exercises\Sets\HistoryController::class, 'store'])->name('fitness.workouts.histories.exercises.sets.store');
                Route::get('/fitness/workouts/histories/{history}/exercises/{exercise_history}/sets/{set_history}', [\App\Http\Controllers\Workouts\Exercises\Sets\HistoryController::class, 'show'])->name('fitness.workouts.histories.exercises.sets.show');
                Route::put('/fitness/workouts/histories/{history}/exercises/{exercise_history}/sets/{set_history}', [\App\Http\Controllers\Workouts\Exercises\Sets\HistoryController::class, 'update'])->name('fitness.workouts.histories.exercises.sets.update');
                Route::delete('/fitness/workouts/histories/{history}/exercises/{exercise_history}/sets/{set_history}', [\App\Http\Controllers\Workouts\Exercises\Sets\HistoryController::class, 'destroy'])->name('fitness.workouts.histories.exercises.sets.destroy');

    Route::get('{type}/{model}/comments', [\App\Http\Controllers\Comments\CommentController::class, 'index'])->name('comments.index');
    Route::post('{type}/{model}/comments', [\App\Http\Controllers\Comments\CommentController::class, 'store'])->name('comments.store');
    Route::get('comments/{comment}', [\App\Http\Controllers\Comments\CommentController::class, 'show'])->name('comments.show');
    Route::put('comments/{comment}', [\App\Http\Controllers\Comments\CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [\App\Http\Controllers\Comments\CommentController::class, 'destroy'])->name('comments.destroy');

    // Widgets
    Route::get('/widgets/user/{view}', [\App\Http\Controllers\Widgets\Users\UserController::class, 'index'])->name('widgets.user.index');
    Route::post('/widgets/user/{view}', [\App\Http\Controllers\Widgets\Users\UserController::class, 'store'])->name('widgets.user.store');
    Route::get('/widgets/user/{view}/{user}', [\App\Http\Controllers\Widgets\Users\UserController::class, 'show'])->name('widgets.user.show');
    Route::get('/widgets/user/{view}/{user}/edit', [\App\Http\Controllers\Widgets\Users\UserController::class, 'edit'])->name('widgets.user.edit');
    Route::delete('/widgets/user/{view}/{user}', [\App\Http\Controllers\Widgets\Users\UserController::class, 'destroy'])->name('widgets.user.destroy');

    Route::get('/widgets/health/sleep', [\App\Http\Controllers\Widgets\Health\SleepController::class, 'index'])->name('widgets.health.sleep.index');
    Route::get('/widgets/health/steps', [\App\Http\Controllers\Widgets\Health\StepsController::class, 'index'])->name('widgets.health.steps.index');
    Route::get('/widgets/health/weight', [\App\Http\Controllers\Widgets\Health\WeightController::class, 'index'])->name('widgets.health.weight.index');

    Route::get('/widgets/data/health/calories', [\App\Http\Controllers\Widgets\Data\Health\CaloriesController::class, 'index'])->name('widgets.data.health.calories.index');
    Route::get('/widgets/data/health/macros', [\App\Http\Controllers\Widgets\Data\Health\MacrosController::class, 'index'])->name('widgets.data.health.macros.index');
    Route::get('/widgets/data/health/weight-development', [\App\Http\Controllers\Widgets\Data\Health\WeightDevelopmentController::class, 'index'])->name('widgets.data.health.weight_development.index');

    Route::get('/widgets/data/time', [\App\Http\Controllers\Widgets\Data\TimeController::class, 'index'])->name('widgets.data.time.index');

});
