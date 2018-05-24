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

// Homepage
Route::get('/', 'HomeController@homepage');

// Auth scaffolding from Laravel
Auth::routes();

// Getting started page
Route::get('getting-started', 'UserController@gettingStarted')->name('user.getting-started');

// Docs CRUD operations
Route::get('docs', 'DocsController@list')->name('docs.list');
Route::get('docs/create', 'DocsController@create')->name('docs.create');
Route::get('docs/{id}', 'DocsController@show')->name('docs.show');
Route::get('docs/{id}/edit', 'DocsController@edit')->name('docs.edit');

Route::get('docs/{id}/pdf', 'DocsController@downloadPDF')->name('docs.pdf');

/*
|--------------------------------------------------------------------------
| Ajax Routes
|--------------------------------------------------------------------------
|
| Group containing all routes under /ajax, meant to respond to XHR requests
| with either JSON or partial HTML responses.
|
*/

Route::group([
    'namespace' =>  'Ajax',
    'prefix' => 'ajax'
], function() {

    Route::get('docs', 'DocsController@list');
    Route::get('docs/{id}.json', 'DocsController@showJSON');
    Route::post('docs', 'DocsController@create');
    Route::put('docs/{id}', 'DocsController@update');
    Route::delete('docs/{id}', 'DocsController@delete');

    Route::get('users.json', 'UserController@listJSON');

});
