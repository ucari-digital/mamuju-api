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

Route::get('/', function () {
    return view('welcome');
});

Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@register');

Route::post('user', 'UserController@user');

Route::post('news', 'MainController@news');
Route::get('news/{id}/{seo}', 'MainController@news_detail');
Route::post('populer', 'MainController@populer');

Route::get('news/{value}', 'MainController@news_search');
Route::get('analytics/{id}', 'MainController@analytics');

Route::get('komentar/{id}', 'MainController@komentar');
Route::post('komentar', 'MainController@komentar_create');

Route::get('iklan/{id}', 'MainController@iklan');

Route::get('kategori', 'KategoriController@kategori');
Route::post('kategori/find', 'KategoriController@kategori_finder');
Route::post('kategori/menu', 'KategoriController@kategori_menu');

Route::get('iklan/{id}', 'MainController@iklan');

Route::post('subscribe', 'SubscribeController@subscribe');
Route::post('unsubscribe', 'SubscribeController@un_subscribe');
