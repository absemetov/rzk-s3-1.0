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
    return view('welcome');
});

/*
 * Photo uploader
 */
Route::get('/upload/{id}', 'UploadController@create')->name('photos.create');

Route::post('/upload/main/{id}', 'UploadController@main')->name('photos.upload.main');

Route::post('/upload/more/{id}', 'UploadController@more')->name('photos.upload.more');

Route::delete('/upload/delete', 'UploadController@delete')->name('photos.upload.delete');

/*
 * Description creator
 */
Route::get('/desc/{id}', 'UploadController@descShow')->name('desc.show');

Route::post('/desc/{id}', 'UploadController@descUpdate')->name('desc.update');




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
