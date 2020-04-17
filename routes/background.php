<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Background'], function () {
  Route::get('/', 'IndexController@index')->name('Background.Index.index');

  Route::resources([
    'books' => 'BooksController',
  ]);
});
