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


Route::post('deploy', 'DeploymentController@store');

Auth::routes();


Route::middleware(['auth'])->group(function () {

    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/work', 'Work\WorkController@index')->name('work.index');

    Route::resource('work/time', 'Work\TimeController');
    Route::get('work/month/{year}/{month}', 'Work\MonthController@show');
    Route::resource('work/year', 'Work\YearController');

});
