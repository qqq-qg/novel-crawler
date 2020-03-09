<?php

use Illuminate\Support\Facades\Route;

// Wap 路由
Route::group([
    'prefix' => '/s',
    'namespace' => 'Wap'
], function () {
    Route::get('/', 'IndexController@index')->name('Index.index');
    Route::get('/r', 'IndexController@readBook')->name('Index.readBook');
    Route::get('/c', 'IndexController@getBookContent')->name('Index.getBookContent');
    Route::get('/l', 'IndexController@getChapterGroup')->name('Index.getChapterGroup');
});

//Test
Route::group([], function () {
    Route::get('test', 'TestController@index')->name('Test.index');

    Route::get('bqg', 'TryBookRuleController@bqg')->name('TryBookRule.bqg');
    Route::get('xbequge', 'TryBookRuleController@xbequge')->name('TryBookRule.xbequge');
});

// 后台路由
$admin = [
    'prefix' => '/',
    'namespace' => 'Admin',
];

Route::group($admin, function () {
    Route::get('login', 'AuthController@getLogin')->name('getAdminLogin');
    Route::post('login', 'AuthController@postLogin')->name('postAdminLogin');

    Route::get('logout', 'AuthController@getLogout')->name('getAdminLogout');
    Route::any('enterpassword', 'AuthController@getEnterpassword')->name('getEnterpassword');

    Route::group(['middleware' => 'admin'], function () {
        Route::get('/', 'IndexController@getIndex')->name('Admin.getIndex');
        Route::post('/', 'IndexController@postIndex')->name('Admin.postIndex');
        Route::any('ajax', 'AjaxController@getIndex')->name('Admin.Ajax');
        //管理员管理
        Route::group(['prefix' => 'manager'], function () {
            Route::get('getIndex', 'ManagerController@getIndex')->name('Manager.getIndex');
            Route::get('getCreate', 'ManagerController@getCreate')->name('Manager.getCreate');
            Route::post('postCreate', 'ManagerController@postCreate')->name('Manager.postCreate');
            Route::get('getUpdate', 'ManagerController@getUpdate')->name('Manager.getUpdate');
            Route::post('postUpdate', 'ManagerController@postUpdate')->name('Manager.postUpdate');
            Route::get('getDelete', 'ManagerController@getDelete')->name('Manager.getDelete');
        });
        //菜单管理
        Route::group(['prefix' => 'menu'], function () {
            Route::get('getIndex', 'MenuController@getIndex')->name('Menu.getIndex');
            Route::get('getCreate', 'MenuController@getCreate')->name('Menu.getCreate');
            Route::post('postCreate', 'MenuController@postCreate')->name('Menu.postCreate');
            Route::get('getUpdate', 'MenuController@getUpdate')->name('Menu.getUpdate');
            Route::post('postUpdate', 'MenuController@postUpdate')->name('Menu.postUpdate');
            Route::get('getDelete', 'MenuController@getDelete')->name('Menu.getDelete');
        });
        //数据管理
        Route::group(['prefix' => 'database'], function () {
            Route::get('getIndex', 'DatabaseController@getIndex')->name('Database.getIndex');
            Route::get('getFields', 'DatabaseController@getFields')->name('Database.getFields');
            Route::get('getRepair', 'DatabaseController@getRepair')->name('Database.getRepair');
            Route::get('getOptimize', 'DatabaseController@getOptimize')->name('Database.getOptimize');
        });
        //系统配置
        Route::group(['prefix' => 'setting'], function () {
            Route::get('getIndex', 'SettingController@getIndex')->name('Setting.getIndex');
            Route::post('postIndex', 'SettingController@postIndex')->name('Setting.postIndex');
            Route::post('postCreate', 'SettingController@postCreate')->name('Setting.postCreate');
            Route::get('getDelete', 'SettingController@getDelete')->name('Setting.getDelete');
            Route::get('getCollect', 'SettingController@getCollect')->name('Setting.getCollect');
            Route::get('getFriendLinks', 'SettingController@getFriendLinks')->name('Setting.getFriendLinks');
            Route::post('postFriendLinks', 'SettingController@postFriendLinks')->name('Setting.postFriendLinks');
            Route::get('getFriendLinkCreate', 'SettingController@getFriendLinkCreate')->name('Setting.getFriendLinkCreate');
            Route::get('getFriendLinkDelete', 'SettingController@getFriendLinkDelete')->name('Setting.getFriendLinkDelete');
            Route::get('getImageUpload', 'SettingController@getImageUpload')->name('Setting.getImageUpload');
            Route::get('getLinkSubmit', 'SettingController@getLinkSubmit')->name('Setting.getLinkSubmit');
            Route::post('postLinkSubmit', 'SettingController@postLinkSubmit')->name('Setting.postLinkSubmit');
        });
        //采集配置
        Route::group(['prefix' => 'collect'], function () {
            Route::get('getIndex', 'CollectController@getIndex')->name('Collect.getIndex');
        });
        //小说设置
        Route::group(['prefix' => 'book'], function () {
            Route::get('/', 'BookController@getIndex')->name('Book.getIndex');

            //栏目分类
            Route::get('/categories', 'BookController@getCategories')->name('Book.getCategories');
            Route::post('/createCategory', 'BookController@createCategory')->name('Book.createCategory');
            Route::get('/deleteCategory', 'BookController@deleteCategory')->name('Book.deleteCategory');

            //采集规则
            Route::get('/collectionRule', 'BookController@collectionRule')->name('Book.collectionRule');
            Route::get('/getCreateCollectionRule', 'BookController@getCreateCollectionRule')->name('Book.getCreateCollectionRule');
            Route::post('/createCollectionRule', 'BookController@createCollectionRule')->name('Book.createCollectionRule');
            Route::get('/deleteCollectionRule', 'BookController@deleteCollectionRule')->name('Book.deleteCollectionRule');
            Route::post('/testCollectionRule', 'BookController@testCollectionRule')->name('Book.testCollectionRule');

            //采集任务
            Route::get('/collectionTask', 'BookController@collectionTask')->name('Book.collectionTask');
            Route::post('/createCollectionTask', 'BookController@createCollectionTask')->name('Book.createCollectionTask');
            Route::get('/deleteCollectionTask', 'BookController@deleteCollectionTask')->name('Book.deleteCollectionTask');


            //Route::get('/create', 'BookController@getCreate')->name('Book.getCreate');
            //Route::post('/create', 'BookController@postCreate')->name('Book.postCreate');
            Route::post('/update', 'BookController@postUpdate')->name('Book.postUpdate');
            Route::get('/delete', 'BookController@getDelete')->name('Book.getDelete');

            Route::get('/createQueue', 'BookController@createQueue')->name('Book.createQueue');
            Route::post('/updateQueue', 'BookController@updateQueue')->name('Book.updateQueue');
            Route::get('/queueNumber', 'BookController@queueNumber')->name('Book.getQueueNumber');


            Route::get('/chapters', 'BookController@getChapters')->name('Book.getChapters');
            Route::get('/updateChapters', 'BookController@updateChapters')->name('Book.updateChapters');
            Route::get('/chapterContent', 'BookController@chapterContent')->name('Book.chapterContent');
            Route::match(['get',
                'post'], '/updateChapter', 'BookController@updateChapter')->name('Book.updateChapter');
            Route::get('/deleteChapter', 'BookController@deleteChapter')->name('Book.deleteChapter');
        });

        Route::group(['prefix' => 'role'], function () {
            Route::get('/', 'RoleController@getIndex')->name('Role.index');
            Route::match(['get', 'post'], '/updateAccess', 'RoleController@updateAccess')->name('Role.updateAccess');
            Route::get('/delete', 'RoleController@getDelete')->name('Role.getDelete');
        });
    });

});

//前台模块路由
/*$home = [
    'prefix' => '/home',
    'namespace' => 'Home',
];
Route::group($home, function () {

    Route::resource(
        'article', 'ArticleController', [

        ]
    );

    Route::get('xiaoshuo/{catid}.html', 'BooksController@getIndex')
        ->where('catid', '\d+')->name('BookCat');
    Route::get('xiaoshuo/{catid}/{id}.html', 'BooksController@getLists')
        ->where(['catid' => '\d+', 'id' => '\d+'])->name('BookLists');
    Route::get('xiaoshuo/{catid}/{id}/{aid}.html', 'BooksController@getContent')
        ->where(['catid' => '\d+', 'id' => '\d+', 'aid' => '\d+'])->name('BookContent');
    Route::get('xiaoshuo/{catid}/{id}/lastest.html', 'BooksController@getLastContent')
        ->where(['catid' => '\d+', 'id' => '\d+'])->name('BookLastContent');
    Route::get('/', 'IndexController@getIndex')->name('getHomeIndex');
    Route::get('/test', 'IndexController@getTest');
});*/
