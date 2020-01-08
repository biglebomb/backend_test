<?php

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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login/password', 'AuthController@loginByPassword');
    $router->post('login/otp', 'AuthController@loginByOTP');
    $router->post('request/otp', 'AuthController@requestOTP');

    $router->get('users', 'UserController@all');
    $router->group(['prefix' => 'user'], function () use ($router){
        $router->get('', 'UserController@currentUser');
        $router->get('{id}', 'UserController@read');
        $router->patch('{id}', 'UserController@update');
        $router->delete('{id}', 'UserController@delete');
    });

});
