<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin'], function () {
  Route::get('/', 'IndexController@index')->name('admin.Index.index');

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
  Route::any('validate-rule', 'CollectionRuleController@validateRule')->name('rules.validate-rule');
});
