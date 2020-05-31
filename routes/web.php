<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/search', 'PhotosController@search')->middleware('auth');

Route::get('/sharedPhoto/{id}/{token}', 'PhotosController@show')->middleware('checkToken');

Route::post('/createToken/{id}', 'PhotosController@createToken')->middleware('auth');

Route::resource('photo', 'PhotosController')->middleware('auth');