<?php

/**
 * Authentication routes
 */

Route::get('/', function() {
    return response('welcome to mybrew, all api routes are prefixed with api/v1');
});

$router->group(['prefix' => 'api/v1'], function() {
    Route::post('auth/login', 'ApiAuthController@login');
    Route::post('auth/register', 'ApiAuthController@register');
});

/**
 * Group all the routes together and make them use the api guard.  Prefix them with api/{version_number}
 */
$router->group(['prefix' => 'api/v1', 'middleware' => 'auth.api'], function() {
    Route::get('profile/user', 'ProfileController@show');
});