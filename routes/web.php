<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::get('/t1', 'TestController@home')->name('home');

Route::get('/t2', 'TestController@getChapter')->name('getChapter');

Route::get('/t3', 'TestController@content')->name('content');

Route::get('/t4', 'TestController@category')->name('category');

Route::get('/test', 'TestController@test')->name('test');
