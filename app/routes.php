<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::group(array('prefix' => '/api'), function() {
	Route::resource('user', 'UsersController');

	Route::post('authenticate', 'AuthenticationController@index');
	Route::post('authenticate/logout', 'AuthenticationController@logout');
	Route::post('authenticate/current', 'AuthenticationController@current');
});

Route::get('/{path?}', function() {
	return View::make('page');
})->where('path', '.*');
