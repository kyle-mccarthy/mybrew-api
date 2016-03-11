<?php

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

    // breweries routes
    Route::get('breweries/', 'BreweryController@index');

    // beer routes
    Route::get('beers/', 'BeerController@index');
});