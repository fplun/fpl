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


Route::group(['namespace' => 'Api', 'middleware' => 'api.log'], function ($router) {
    $router->post('login', 'AuthenticateController@login');
    $router->post('register', 'AuthenticateController@register');
    $router->post('smsCode', 'AuthenticateController@smsCode');

    $router->group(['middleware' => 'auth.api'], function($router) {
        $router->get('/', 'IndexController@index');

    });
});
