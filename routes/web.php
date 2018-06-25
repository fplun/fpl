<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
//Route::get('/', 'Home/LoginController@login');
//Route::match(['get','post'],'login', 'LoginController@login');
//Route::match(['get','post'],'register', 'LoginController@register');
Route::group(['namespace'=>'Home'],function (){
    Route::get('/', 'LoginController@login');
    Route::get('/english',function (){
        session(['lang'=>'english']);
        return redirect()->back();
    });
    Route::get('/chinese',function (){
        session(['lang'=>'']);
        return redirect()->back();
    });
    //登录
    Route::get('/login', 'LoginController@login');
    Route::get('/verify', 'LoginController@verify');
    Route::post('/login_make', 'LoginController@login_make');
    //注册
    Route::get('/register', 'LoginController@register');
    Route::get('/register/{code}', 'LoginController@register');
    Route::post('/register_make', 'LoginController@register_make');
    Route::post('/send_code', 'LoginController@send_code');

    //忘记密码
    Route::get('/forget', 'LoginController@forget');
    Route::post('/forget_make', 'LoginController@forget_make');
    Route::group(['middleware'=>'auth.web'],function (){
        //个人中心
        Route::get('center/index', 'CenterController@index');
        Route::get('/index', 'CenterController@home'); //首页
        Route::get('center/info', 'CenterController@info');
        Route::get('center/sq', 'CenterController@sq');
        Route::get('center/sc', 'CenterController@sc');
        Route::post('center/sc_make', 'CenterController@sc_make');
        Route::get('wallet/index', 'CenterController@wallet');
        Route::post('center/info_make', 'CenterController@info_make');

        Route::get('center/password', 'CenterController@password');
        Route::post('/center/password_make', 'CenterController@password_make');

        Route::get('center/contact', 'CenterController@contact');
        Route::post('center/contact_make', 'CenterController@contact_make');
//        Route::get('center/help', 'CenterController@help');
//        Route::get('center/news', 'CenterController@news');
        Route::get('/out', 'CenterController@out');
        Route::get('center/read', 'CenterController@read');

        //交易
        Route::group(['middleware'=>'deal'],function(){
            Route::get('deal/index', 'DealController@index');
            Route::post('deal/deal_password', 'DealController@deal_password');
            Route::post('deal/buy_make', 'DealController@buy_make');
            Route::post('deal/buy_accept', 'DealController@buy_accept');
            Route::post('deal/sell_make', 'DealController@sell_make');

        });
        Route::get('deal/my_deal', 'DealController@my_deal');
        Route::post('deal/buy_cancel', 'DealController@buy_cancel');
        Route::post('deal/sell_cancel', 'DealController@sell_cancel');
        Route::post('deal/upload_img', 'DealController@upload_img');
        Route::post('deal/look_img', 'DealController@look_img');
        Route::post('deal/deal_finish', 'DealController@deal_finish');
        Route::post('deal/deal_cancel', 'DealController@deal_cancel');
        Route::post('deal/look_info', 'DealController@look_info');
        Route::post('deal/complaint', 'DealController@complaint');


        //我的矿机
        Route::get('miner/index', 'MinerController@index');
        Route::get('miner/upcomputing', 'MinerController@upcomputing');
        Route::get('miner/profit', 'MinerController@profit');
        Route::post('miner/buy_make', 'MinerController@buy_make');
        Route::get('miner/run/{id}', 'MinerController@run');
        Route::get('miner/run_make/{id}', 'MinerController@run_make');
        //矿机商城
        Route::get('shop/index', 'ShopController@index');
        Route::get('shop/buy/{id}', 'ShopController@buy');
        Route::get('shop/buy_make/{id}', 'ShopController@buy_make');
        //工会
        Route::get('union/index', 'UnionController@index');
        Route::get('union/rank1', 'UnionController@rank1');
        Route::get('union/rank2', 'UnionController@rank2');
        Route::get('union/rank3', 'UnionController@rank3');
        Route::get('union/profit', 'UnionController@profit');
        Route::get('union/recruit', 'UnionController@recruit');
        Route::post('union/get_subordinate', 'UnionController@get_subordinate');
        Route::get('union/myfriend', 'UnionController@myfriend');
    });
});
