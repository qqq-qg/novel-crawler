<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Background'], function () {
  Route::get('/', 'IndexController@index')->name('Background.Index.index');

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
