<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::auth();

Route::get('/',['as' => 'live', 'uses' => 'HomeController@live']);
Route::get('finished',['as' => 'home', 'uses' => 'HomeController@index']);
Route::get('ready',['as' => 'ready', 'uses' => 'HomeController@ready']);

Route::group(['prefix' => 'ajax'], function () {
    Route::get('offer', ['as' => 'offer', 'uses' => 'HomeController@performRequest']);
});

