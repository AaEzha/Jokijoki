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

Route::name('client.')->middleware('auth')->prefix('client')->group(function() {
    Route::get('/', 'ClientController@name')->name('name');
    Route::post('/', 'ClientController@name')->name('name');
    Route::get('/jobs', 'ClientController@index')->name('index');
    Route::post('/jobs', 'ClientController@index')->name('index');
    Route::get('/files/{id}', 'ClientController@files')->name('files');
    Route::post('/files/{id}', 'ClientController@files')->name('files');
});
