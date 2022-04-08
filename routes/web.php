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


//Common Routes
Route::get('register','App\Http\Controllers\AuthController@get_register')->name('get_register');
Route::post('register','App\Http\Controllers\AuthController@register')->name('register');
Route::get('login','App\Http\Controllers\AuthController@get_login')->name('get_login');
Route::post('login','App\Http\Controllers\AuthController@login')->name('login');
Route::get('activate_account/{token}','App\Http\Controllers\AuthController@activate_account')->name('activate_account');
Route::get('forgot_password','App\Http\Controllers\AuthController@get_forgot_password')->name('get_forgot_password');
Route::post('forgot_password','App\Http\Controllers\AuthController@forgot_password')->name('forgot_password');
Route::get('reset_password/{token}','App\Http\Controllers\AuthController@reset_password')->name('reset_password');
Route::post('update_password','App\Http\Controllers\AuthController@update_password')->name('update_password');
Route::get('logout','App\Http\Controllers\AuthController@logout')->name('logout');


//Admin Routes
Route::name('super.')->middleware('is_admin:1')->prefix('super')->group(function (){
    Route::get('dashboard','App\Http\Controllers\AdminController@getDashboard')->name('dashboard');

    //Users
    Route::get('users','App\Http\Controllers\AdminController@getUsers')->name('get_users');
    Route::post('users','App\Http\Controllers\AdminController@users')->name('users');
    Route::get('approve_user/{id}','App\Http\Controllers\AdminController@approveUser')->name('approve_user');
    Route::get('delete_user/{id}','App\Http\Controllers\AdminController@deleteUser')->name('delete_user');
    Route::get('add_user','App\Http\Controllers\AdminController@getAddUser')->name('get_add_user');
    Route::post('save_user','App\Http\Controllers\AdminController@saveUser')->name('save_user');

    //Category
    Route::get('category','App\Http\Controllers\AdminController@getCategory')->name('get_category');
    Route::post('category','App\Http\Controllers\AdminController@category')->name('category');
    Route::get('edit_category/{id}','App\Http\Controllers\AdminController@editCategory')->name('edit_category');
    Route::get('approve_category/{id}','App\Http\Controllers\AdminController@approveCategory')->name('approve_category');
    Route::get('delete_category/{id}','App\Http\Controllers\AdminController@deleteCategory')->name('delete_category');
    Route::get('add_category','App\Http\Controllers\AdminController@getAddCategory')->name('get_add_category');
    Route::post('save_category','App\Http\Controllers\AdminController@saveCategory')->name('save_category');

    //Post
    Route::get('posts','App\Http\Controllers\AdminController@getPosts')->name('get_posts');
    Route::post('posts','App\Http\Controllers\AdminController@posts')->name('posts');
    Route::get('edit_post/{id}','App\Http\Controllers\AdminController@editPost')->name('edit_post');
    Route::get('delete_post/{id}','App\Http\Controllers\AdminController@deletePost')->name('delete_post');
    Route::get('publish_post/{id}','App\Http\Controllers\AdminController@publishPost')->name('publish_post');
    Route::get('add_post','App\Http\Controllers\AdminController@getAddPost')->name('get_add_post');
    Route::post('save_post','App\Http\Controllers\AdminController@savePost')->name('save_post');

    //Comments
    Route::get('posts/{id}/comments','App\Http\Controllers\AdminController@getComments')->name('get_comments');
    Route::post('posts/{id}/comments','App\Http\Controllers\AdminController@comments')->name('comments');
    Route::get('edit_comment/{id}','App\Http\Controllers\AdminController@editComments')->name('edit_comment');
    Route::get('delete_comment/{id}','App\Http\Controllers\AdminController@deleteComments')->name('delete_comment');
    Route::get('posts/{id}/add_comment','App\Http\Controllers\AdminController@getAddComments')->name('get_add_comment');
    Route::post('save_comment','App\Http\Controllers\AdminController@saveComments')->name('save_comments');
});


//Blog
Route::name('blog.')->group(function (){
    Route::get('/', 'App\Http\Controllers\BlogController@posts'); ///ALLL LIKE THIS
    Route::get('/posts/category/{id}/{category}','App\Http\Controllers\BlogController@postByCategory')->name('post_by_category');
    Route::get('/posts/user/{id}/{user}','App\Http\Controllers\BlogController@postByUsers')->name('post_by_users');
    Route::get('/post/{post_url}','App\Http\Controllers\BlogController@singleBlog')->name('single_blog');
    Route::post('/post/{id}/comment','App\Http\Controllers\BlogController@comment')->name('comment');
});
