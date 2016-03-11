<?php

<<<<<<< HEAD
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
=======
/**
 * Authentication routes
 */

Route::get('/', function() {
    return response('welcome to mybrew, all api routes are prefixed with api/v1');
});

$router->group(['prefix' => 'api'], function() {
    Route::post('auth/login', 'ApiAuthController@login');
    Route::post('auth/register', 'ApiAuthController@register');
});

/**
 * Group all the routes together and make them use the api guard.  Prefix them with api/{version_number}
 */
$router->group(['prefix' => 'api', 'middleware' => 'auth.api'], function() {
    Route::get('profile/user', 'ProfileController@show');

    // color routes
    Route::get('colors/', 'ColorController@index');
    Route::post('colors/beers', 'ColorController@beers');

    // breweries routes
    Route::get('breweries/', 'BreweryController@index');

    // beer routes
    Route::get('beers/', 'BeerController@index');
});
>>>>>>> 0b925e97878510ba1e5086461f2121bab40c6c91
