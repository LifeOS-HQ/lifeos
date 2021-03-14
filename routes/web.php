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

Route::get('/test', function () {
    return 'test';
});

Route::post('deploy', 'DeploymentController@store');

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home/work', 'Home\Work\WorkController@show')->name('home.work.show');
    Route::get('/home/rentablo', 'Home\Rentablo\RentabloController@index')->name('home.rentablo.index');
    Route::get('/home/server', 'Home\Servers\StatusController@index')->name('home.server.index');

    Route::resource(\App\Models\Activities\Activity::ROUTE_NAME, 'Activities\ActivityController');
    Route::resource('health', 'Health\HealthController');

    Route::resource('journal', 'Journals\JournalController');
    Route::resource('journal.activity', 'Journals\Activities\ActivityController');
    Route::resource('journal.gratitude', 'Journals\Gratitude\GratitudeController');
    Route::put('/journal/{journal}/sort/gratitude', 'Journals\Gratitude\SortController@update')->name('journal.sort.gratitude.update');
    Route::put('/journal/{journal}/gratitude/{gratitude}/gamechanger', 'Journals\Gratitude\GameChangerController@update')->name('journal.gratitude.gamechanger.update');
    Route::resource('journal.rating', 'Journals\RatingController');
    Route::put('/journal/{journal}/sort/rating', 'Journals\Ratings\SortController@update')->name('journal.sort.rating.update');

    Route::resource('lifearea', 'Lifeareas\LifeareaController');
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

    Route::get('/work', 'Work\WorkController@index')->name('work.index');

    Route::resource('work/time', 'Work\TimeController');
    Route::get('work/month/{year}/{month}', 'Work\Months\ChartController@show');

    Route::resource('work/month', 'Work\MonthController');

    Route::resource('work/year', 'Work\YearController');

    Route::resource('workouts/exercises', 'Exercises\ExerciseController');
    Route::resource('workouts', 'Workouts\WorkoutController');
    Route::resource('workouts.histories', 'Workouts\HistoryController');

});
