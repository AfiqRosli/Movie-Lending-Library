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
    Route::get('movie', function () {
        return view('movie.index');
    })->name('index');
});

Route::name('member.')->group(function () {
    Route::get('member', function () {
        return view('member.index');
    })->name('index');
});

Route::name('lend.')->group(function () {
    Route::get('lend', function () {
        return view('lend.index');
    })->name('index');
});

Route::name('return.')->group(function () {
    Route::get('return', function () {
        return view('return.index');
    })->name('index');
});
