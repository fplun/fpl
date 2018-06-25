<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('articles', ArticleController::class);
    $router->resource('users',UserController::class);
    $router->resource('complaint',ComplaintController::class);
    $router->resource('message',MessageController::class);
    $router->resource('set/miner',MinerController::class);
    $router->resource('deal',DealController::class);
    $router->resource('sys',SysController::class);
    $router->resource('price', PriceController::class); //PTA价格设置
    $router->get('users/status/{s}/{id}','UserController@status');
    $router->get('give','GiveController@index');
    $router->any('/give/giveMiner', 'GiveController@giveMiner'); //系统赠送矿机处理
    $router->any('/give/giveCoin', 'GiveController@giveCoin');
    $router->any('/team', 'TeamController@index');
});