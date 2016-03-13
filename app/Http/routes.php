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
    // user routes
    Route::get('profile/user', 'ProfileController@show');
    Route::post('profile/user/update', 'ProfileController@update');

    // color routes
    Route::get('colors/', 'ColorController@index');
    Route::post('colors/beers', 'ColorController@beers');

    // breweries routes
    Route::get('breweries/', 'BreweryController@index');

    // beer routes
    Route::get('beers/', 'BeerController@index');
    Route::get('beers/beer/{id}', 'BeerController@beer');
});
