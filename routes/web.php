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

    Route::resource('journal', 'Journals\JournalController');
    Route::resource('journal.gratitude', 'Journals\Gratitude\GratitudeController');
    Route::put('/journal/{journal}/sort/gratitude', 'Journals\Gratitude\SortController@update')->name('journal.sort.gratitude.update');
    Route::put('/journal/{journal}/gratitude/{gratitude}/gamechanger', 'Journals\Gratitude\GameChangerController@update')->name('journal.gratitude.gamechanger.update');
    Route::resource('journal.rating', 'Journals\RatingController');
    Route::put('/journal/{journal}/sort/rating', 'Journals\Ratings\SortController@update')->name('journal.sort.rating.update');

    Route::resource('lifearea', 'Lifeareas\LifeareaController');
    Route::resource('lifearea.scale', 'Lifeareas\ScaleController');

    Route::resource('review', 'Reviews\ReviewController');
    Route::resource('review.block', 'Reviews\BlockController');
    Route::resource('review.lifearea', 'Reviews\LifeareaController');

    Route::get('/work', 'Work\WorkController@index')->name('work.index');

    Route::resource('work/time', 'Work\TimeController');
    Route::get('work/month/{year}/{month}', 'Work\Months\ChartController@show');

    Route::resource('work/month', 'Work\MonthController');

    Route::resource('work/year', 'Work\YearController');

});
