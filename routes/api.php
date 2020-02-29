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
Route::namespace('Api')->group(function() {
	//for creating account
    Route::post('/register','AccountsController@create');
    //for funding
    Route::post('/credit','AccountsController@credit');
    //for withdrawing
    Route::post('/withdraw','AccountsController@debit');

    Route::post('/update', 'AccountsController@update');
});

