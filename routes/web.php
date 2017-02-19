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

Route::get('/', array('as' => 'frontpage', 'uses' => 'FrontPageController@showFrontPage'));

//basic account routes
Auth::routes();
Route::get('/home', 'HomeController@index');
Route::get('/account/settings', array('as' => 'account.settings', 'uses' => 'AccountController@showSettings'));
Route::post('/account/settings', array('as' => 'account.settings.update', 'uses' => 'AccountController@updateSettings'));
Route::get('/account/burn', array('as' => 'account.burn', 'uses' => 'AccountController@showBurnActivation'));
Route::get('/account/burn/check', array('as' => 'account.burn.check', 'uses' => 'AccountController@checkBTCBurned'));


//user-submitted posts
Route::get('/post', array('as' => 'posts.new', 'uses' => 'PostController@newPostForm'));
Route::post('/post', array('as' => 'posts.new.submit', 'uses' => 'PostController@newPost'));

//Front page, tags and posts
Route::get('/p/{slug}', array('as' => 'posts.view', 'uses' => 'FrontPageController@showPost'));
Route::post('/p/{slug}', array('as' => 'posts.view', 'uses' => 'FrontPageController@submitComment'));
Route::get('/p/{slug}/edit', array('as' => 'posts.edit', 'uses' => 'PostController@editPostForm'));
Route::post('/p/{slug}/edit', array('as' => 'posts.edit.submit', 'uses' => 'PostController@editPost'));
Route::get('/p/{slug}/delete', array('as' => 'posts.delete', 'uses' => 'PostController@deletePost'));
Route::get('/p/{slug}/comment/{id}', array('as' => 'posts.view', 'uses' => 'FrontPageController@showEditComment'));
Route::post('/p/{slug}/comment/{id}', array('as' => 'posts.view', 'uses' => 'FrontPageController@editComment'));
Route::get('/t/saved', array('as' => 'posts.tagged', 'uses' => 'FrontPageController@showSavedTaggedPosts'));
Route::get('/t/{tags}', array('as' => 'posts.tagged', 'uses' => 'FrontPageController@showTaggedPosts'));


