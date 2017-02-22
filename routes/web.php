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

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/install', ['as' => 'install', 'uses' => 'HomeController@install']);


Route::post('do_period', ['as' => 'do_period', 'uses' => 'HomeController@do_period']);
Route::post('get_statics', ['as' => 'get_statics', 'uses' => 'HomeController@get_statics']);
Route::post('do_actions', ['as' => 'do_actions', 'uses' => 'HomeController@do_actions']);
