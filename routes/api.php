<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group( function () {

    Route::get('/work/time', 'Api\Work\Times\TimeController@index')->name('api.work.time.index');
    Route::post('/work/time/import', 'Api\Work\Times\TimeController@store')->name('api.work.time.store');

});
