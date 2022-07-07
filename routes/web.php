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

$config = array_merge(config('apilog.route'), ['namespace' => 'AWT\\Http\\Controllers']);
Route::group($config, function($router)
{
    Route::get('/', 'ApiLogController@index')->name("apilogs.index");
    Route::delete('/delete', 'ApiLogController@delete')->name("apilogs.deletelogs");
});
