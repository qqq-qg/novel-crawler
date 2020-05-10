<?php

use Illuminate\Support\Facades\Route;

// Wap 路由
Route::group(['prefix' => '/s', 'namespace' => 'Wap'], function () {
  Route::get('/', 'IndexController@index')->name('Index.index');
  Route::get('/r', 'IndexController@readBook')->name('Index.readBook');
  Route::get('/c', 'IndexController@getBookContent')->name('Index.getBookContent');
  Route::get('/l', 'IndexController@getChapterGroup')->name('Index.getChapterGroup');
});

//前台模块路由
Route::group(['prefix' => '/', 'namespace' => 'Home'], function () {
  Route::get('/', 'IndexController@getIndex')->name('getHomeIndex');

  Route::get('category/{catid}.html', 'BooksController@getIndex')
    ->where('catid', '\d+')
    ->name('BookCat');
  Route::get('category/{catid}/{id}.html', 'BooksController@getLists')
    ->where(['catid' => '\d+', 'id' => '\d+'])
    ->name('BookLists');
  Route::get('category/{catid}/{id}/{aid}.html', 'BooksController@getContent')
    ->where(['catid' => '\d+', 'id' => '\d+', 'aid' => '\d+'])
    ->name('BookContent');
  Route::get('category/{catid}/{id}/lastest.html', 'BooksController@getLastContent')
    ->where(['catid' => '\d+', 'id' => '\d+'])
    ->name('BookLastContent');
});

//后台管理模块
Route::group(['prefix' => '/admin'], function () {
  //验证
  Route::group(['namespace' => 'Auth'], function () {
    Route::get('login', 'LoginController@loginForm')->name('login-form');
    Route::post('login', 'LoginController@login')->name('admin-login');
    Route::any('login-out', 'LoginController@logout')->name('admin-login-out');
  });

  //管理
  Route::group(['namespace' => 'Admin', 'middleware' => ['logged']], function () {
    Route::get('/', 'IndexController@index')->name('admin.Index.index');
    Route::any('validate-rule', 'CollectionRuleController@validateRule')->name('rules.validate-rule');

    Route::resources([
      'books' => 'BooksController',
      'chapters' => 'BooksChapterController',
      'categories' => 'CategoryController',
      'rules' => 'CollectionRuleController',
      'tasks' => 'CollectionTaskController',
      'links' => 'LinkController',
      'managers' => 'ManagerController',
      'menus' => 'MenuController',
      'settings' => 'SettingController',
    ]);
  });
});
