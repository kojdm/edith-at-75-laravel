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

Route::get('/', 'GalleryController@index')->name('home');

Route::post('deleteimage', 'UploadsController@deleteImage');

Route::post('upload', 'UploadsController@store')->name('upload.store');

Route::post('finalupload', 'UploadsController@finalUpload');
Route::post('checksinger', 'UploadsController@checkSinger');

Route::get('uploadlist', 'UploadsController@listImages')->name('upload.list');

Route::get('simpleupload', 'SimpleUploadsController@index')->name('simpleupload.index');
Route::post('simpleupload', 'SimpleUploadsController@store')->name('simpleupload.store');
Route::get('simpleupload/captions', 'SimpleUploadsController@captions')->name('simpleupload.captions');
Route::post('simpleupload/captions', 'SimpleUploadsController@storeCaptions')->name('simpleupload.storeCaptions');

Route::post('simpleupload/delete', 'SimpleUploadsController@deleteImages')->name('simpleupload.deleteImages');


Route::get('/test', function(){
    $images = \DB::table('uploads')->get();
    $data = [
        'images' => $images,
    ];

    return view('test')->with($data);
});
