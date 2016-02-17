<?php

/**
 * Authentication routes
 */


/**
 * Group all the routes together and make them use the api guard.  Prefix them with api/{version_number}
 */
$router->group(['prefix' => 'api/v1', 'middleware' => 'auth:api'], function() {


});