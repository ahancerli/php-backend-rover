<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Using Plateau Prefix
$router->group(['prefix' => '/plateau'], function() use ($router)
{
    $router->get('/list', 'PlateauController@list');
    $router->post('/create', 'PlateauController@create');
});

// Using Rover Prefix
$router->group(['prefix' => 'rover'], function() use ($router)
{
    $router->get('/list', 'RoverController@list');
    $router->post('/create', 'RoverController@create');
    $router->get('/getState{id}', 'RoverController@getState');
    $router->put('/setState/{id}', 'RoverController@setState');
});
