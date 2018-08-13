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

Route::post('headline', 'MainController@headline');

Route::post('news', 'MainController@news');
Route::get('news/{id}/{seo}', 'MainController@news_detail');
Route::post('news/search', 'MainController@news_search');
Route::get('analytics/{id}', 'MainController@analytics');

Route::get('komentar/{id}', 'MainController@komentar');
Route::post('komentar', 'MainController@komentar_create');

Route::post('profil', 'MainController@profil');

Route::get('iklan/{id}', 'MainController@iklan');
