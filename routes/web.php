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
Route::get('komentar/{id}', 'MainController@komentar');
Route::get('analytics/{id}', 'MainController@analytics');
Route::get('profil/get/{role}/{status}', 'MainController@profil_get');
