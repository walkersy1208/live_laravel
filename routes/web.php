<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//dingo api test
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->get('test', '\App\Http\Controllers\Admin\apiController@login');
});

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

Route::get('/test', 'homeController@index');
Route::get('/params_get', 'homeController@params');
Route::get('testmiddileware', 'homeController@testMiddile');
Route::get('/collection_test', 'homeController@collect');
Route::get('/register/{name}/{password}', 'registerController@index');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/register', 'registerController@index');
Route::post('/register_form', 'registerController@register');
Route::post('/checkEmailUnique', 'registerController@uniqueEmail');
Route::get('/login', 'loginController@index');
Route::get('/logout', 'loginController@logout');
Route::get('/collect', 'loginController@test_collection');
Route::post('/check', 'loginController@check');

//Article Route
Route::get('/articles', 'ArticlesController@index');
Route::get('/tag_articles/{tag}', 'ArticlesController@tag_articles');
//Route::get('/articles_v2', 'ArticlesController@index');
Route::post('/checkUserLike', 'ArticlesController@checkUserLike');
Route::post('/articles_add', 'ArticlesController@articles_add');
Route::post('/articles_del/{article}', 'ArticlesController@destroy')->name('article.del');
Route::post('/articles_like', 'ArticlesController@handlelike');
Route::get('/articles_edit/{article}', 'ArticlesController@edit')->name('article.edit');
Route::post('/articles_update/{article}', 'ArticlesController@update')->name('article.update');
Route::post('/carousel_items', 'ArticlesController@carousel_items');
Route::get('/articles_add', 'ArticlesController@create')->name('article.create');
Route::post('/articles_save', 'ArticlesController@store')->name('article.store');
Route::post('/article_tags', 'ArticlesController@article_tags')->name('article.tags');
Route::get('/article_sort/{param}', 'ArticlesController@article_sort')->name('article_sort');
//Route::post('/tags_search', 'ArticlesController@tags')->name('article.tags');

//admin
Route::get('/articles_show/{id}', 'ArticlesController@show')->name('article.show');

Route::group(['prefix' => 'admin', 'middileware' => 'web', 'namespace' => 'Admin'], function () {
    Route::get('/login', 'adminController@login');
    Route::post('/checkLogin', 'adminController@checkLogin');
    Route::get('/index', 'adminController@index');
    Route::get('/showAdmin', 'adminController@showAdmin');
    Route::get('/showRole', 'adminController@showRole');
    Route::post('/add_role', 'adminController@addRole');
    Route::post('/edit_role/{id}', 'adminController@editRole');
    Route::post('/delete_role/{id}', 'adminController@deleteRole');
    //notice
    Route::get('/notice_show', 'noticeController@index');
    Route::post('/add_notice', 'noticeController@add');
    Route::post('/edit_notice/{id}', 'noticeController@edit');
    Route::post('/delete_notice/{id}', 'noticeController@delete');
});
//throttle:60,1,
Route::group(['prefix' => 'api', 'middleware' => ['throttle:60,1'], 'namespace' => 'Admin'], function () {
    Route::get('/login', 'apiController@index');
    Route::post('/login', 'apiController@login');
    Route::post('/logout', 'apiController@logout');
    Route::get('/test', 'apiController@test');
    Route::get('/showAdmin', 'apiController@showAdmin');
    Route::get('/showRole', 'apiController@showRole');
    Route::post('/delete_admin/{id}', 'apiController@delete_admin');
    Route::post('/delete_article/{id}', 'apiController@delete_article');
    //Route::post('/add_role', 'apiController@addRole');
    // Route::post('/edit_role/{id}', 'apiController@editRole');
    // Route::post('/delete_role/{id}', 'apiController@deleteRole');
    //admin
    Route::post('/edit_admin/{id}', 'apiController@admin_edit');
    Route::post('/add_admin', 'apiController@add_admin');
    Route::get('/articles_review', 'apiController@articles_review');
    Route::get('/articles_delete', 'apiController@articles_delete');
    Route::get('/carousel', 'apiController@carousel');
    Route::post('/articles_status_change', 'apiController@articles_status_change');
    Route::get('/upload', 'apiController@upload_image');
    Route::post('/upload', 'apiController@hanlde_upload');
    Route::post('/add_carousel', 'apiController@add_carousel');
    Route::post('/del_carousel', 'apiController@del_carousel');
    Route::post('/changeSwitchStatus', 'apiController@changeSwitchStatus');
    Route::post('/sort_table', 'apiController@sort_table');
    Route::post('/sort_table_articles', 'apiController@sort_table_articles');
    Route::post('/articles_update/{article}', 'apiController@update');
    Route::get('/me', 'apiController@me');
});

Route::group(['prefix' => 'api_passport', 'middleware' => 'throttle:60,1', 'namespace' => 'passport'], function () {
    Route::get('/login', 'apiController@index');
    Route::post('/login', 'apiController@login');
    Route::post('/logout', 'apiController@logout');
    Route::get('/test', 'apiController@test');
    Route::get('/showAdmin', 'apiController@showAdmin');
    Route::get('/showRole', 'apiController@showRole');
    Route::post('/add_role', 'apiController@addRole');
    Route::post('/edit_role/{id}', 'apiController@editRole');
    Route::post('/delete_role/{id}', 'apiController@deleteRole');
    //admin
    Route::post('/edit_admin/{id}', 'apiController@admin_edit');
    Route::post('/add_admin', 'apiController@add_admin');
    Route::get('/articles_review', 'apiController@articles_review');
    Route::get('/carousel', 'apiController@carousel');
    Route::post('/articles_status_change', 'apiController@articles_status_change');
    Route::get('/upload', 'apiController@upload_image');
    Route::post('/upload', 'apiController@hanlde_upload');
    Route::post('/add_carousel', 'apiController@add_carousel');
    Route::post('/del_carousel', 'apiController@del_carousel');
    Route::post('/changeSwitchStatus', 'apiController@changeSwitchStatus');
    Route::post('/sort_table', 'apiController@sort_table');
    Route::get('/me', 'apiController@me');
});

//notification
Route::get('/notification', 'ArticlesController@notification');
Route::get('/read_all_notifications', 'ArticlesController@read_all_notifications');
Route::get('/read_notifications', 'ArticlesController@read_notifications');
Route::post('/mark_read', 'ArticlesController@mark_read');

Route::group(['prefix' => 'spa',  'namespace' => 'spa'], function () {
    Route::post('/login', 'spaController@login');
    Route::post('/logout', 'spaController@logout');
    Route::post('/articles_update/{article}', 'spaController@update');
    Route::post('/articles_del/{article}', 'spaController@destroy');
    Route::post('/tag_articles', 'spaController@tag_articles');
    Route::post('/get_articles', 'spaController@get_articles');
    Route::post('/articles_del/{article}', 'spaController@destroy');
    Route::post('/user', 'spaController@user');
    Route::post('/checkUserLike', 'spaController@checkUserLike');
    Route::post('/articles_like', 'spaController@handlelike');
    Route::post('/articles_add', 'spaController@articles_add');
    Route::post('/article_sort', 'spaController@article_sort');

    Route::post('/read_all_notifications', 'spaController@read_all_notifications');
    Route::post('/read_notifications', 'spaController@read_notifications');
    Route::post('/mark_read', 'spaController@mark_read');

    Route::get('/{any}', 'spaController@index')->where('any', '.*');
});
