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

$router->get('/', [
    'as' => 'index', 'uses' => 'Controller@index'
]);

$router->group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'], function () use ($router) {
    $router->get('/', function () {
    });
    $router->post('platforms', 'PlatformController@store');
    /*
$router->get($uri, $callback);
$router->post($uri, $callback);
$router->put($uri, $callback);
$router->patch($uri, $callback);
$router->delete($uri, $callback);
     */
});
