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
//首页信息
Route::get('/','IndexController@index');
//收藏
Route::get('/shoucang','SaveController@index');
//用户
Route::get('/user','UserController@index');
//登录
Route::get('/login','LoginController@login');
//注册
Route::get('/register','LoginController@register');
//商品详情
Route::get('/proinfo/{id}','GoodsController@proinfo');
//商品展示
Route::get('/prolist/{id?}','GoodsController@index');
//购物车展示
Route::get('/car','CarController@index');
//加入购物车
Route::post('/carAdd','CarController@create');
//加减号失去焦点
Route::post('/changeNum','CarController@num');
//总价
Route::post('/totalPrice','CarController@totalPrice');
//去结算
Route::get('/balance','OrderController@index');
//收货地址管理
Route::get('/add-address','AddressController@index');
//收货地址添加表
Route::get('/address','AddressController@create');
//三级联动
Route::post('/getNextArea','AddressController@getNextArea');
//收货地址添加
Route::get('/addAddress','AddressController@addAddress');
//收货地址默认
Route::post('/addressDefault','AddressController@addressDefault');
//提交订单
Route::post('/entrega','OrderController@entrega');
//订单提交成功
Route::get('/triunfo/{order_no}','OrderController@triunfo');
//支付
Route::get('/pay/{orderno}','OrderController@alipay');
//同步支付
Route::get('/paypay','OrderController@paypay');
//异步支付
Route::post('/notifypay','OrderController@notifypay');
//包含文件
Route::get('public/footer','IndexController@footer');
Route::get('public/top','IndexController@top');




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//微信支付
Route::get('/weixin/paypay/{orderno}','weixin\WxpayController@paypay');
//微信支付回调地址
Route::post('/weixin/notify','weixin\WxpayController@notify');