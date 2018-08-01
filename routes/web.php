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

Route::get('/', function () {
    return view('main');
});

Route::get('/menu', function () {
    return view('menu');
});

Route::get('/admin', 'Controller@adminPanel');
Route::post('/admin/userlevel', 'Controller@adminLevel');
Route::post('/admin/article', 'Controller@adminArticle');
Route::get('/admin/article{id}', 'Controller@editArticle')->where('id', '\d+');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/new', 'Controller@editArticle');
Route::post('/insert', 'Controller@insertArticle');
Route::post('/newsection', 'Controller@insertSection');
Route::get('/articles', 'Controller@listArticles');
Route::get('article{id}', 'Controller@showArticle')->where('id', '\d+');

Route::get('/sections', 'Controller@showSection');
Route::get('/section{id}', 'Controller@showSection')->where('id', '\d+');
Route::post('/admin/section', 'Controller@adminSection');

Route::get('/json', 'Controller@generateJSON');
