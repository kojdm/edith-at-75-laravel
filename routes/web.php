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

Route::get('/', 'GalleryController@index');

Route::post('deleteimage', 'UploadsController@deleteImage');

Route::post('upload', 'UploadsController@store')->name('upload.store');

Route::post('finalupload', 'UploadsController@finalUpload');
Route::post('checksinger', 'UploadsController@checkSinger');

Route::get('uploadlist', 'UploadsController@listImages')->name('upload.list');

Route::get('/test', function(){
    return view('test');
});
