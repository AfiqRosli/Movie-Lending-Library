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
    Route::patch('movie/{movie}', 'MovieController@update')->name('update');
    Route::delete('movie/{movie}', 'MovieController@destroy')->name('delete');
});

Route::name('member.')->group(function () {
    Route::get('member', 'MemberController@index')->name('index');
    Route::post('member', 'MemberController@store')->name('store');
    Route::patch('member/{member}', 'MemberController@update')->name('update');
    Route::delete('member/{member}', 'MemberController@destroy')->name('delete');
});

Route::name('lend.')->group(function () {
    Route::get('lend', 'LendController@home')->name('home');
    Route::get('lend/list', 'LendController@index')->name('index');
    Route::get('lend/create', 'LendController@create')->name('create');

    Route::post('lend', 'LendController@store')->name('store');

    Route::patch('lend/{lend}', 'LendController@update')->name('update');
});
