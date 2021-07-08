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
    return view('index');
})->name('index');

Route::name('movie.')->group(function () {
    Route::get('movie', 'MovieController@index')->name('index');
    Route::post('movie', 'MovieController@store')->name('store');
});

Route::name('member.')->group(function () {
    Route::get('member', 'MemberController@index')->name('index');
});

Route::name('lend.')->group(function () {
    Route::get('lend', 'LendController@index')->name('index');
});

Route::name('return.')->group(function () {
    Route::get('return', 'ReturnController@index')->name('index');
});
