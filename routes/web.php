<?php

use Illuminate\Support\Facades\Route;

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

Route::get('resume', function () {
    return view('resume');
});

Route::get('music', function () {
    return view('music');
});

Route::get('video', function () {
    return view('video');
});

Route::resource('news', \App\Http\Controllers\NewsController::class);

Route::resource('blog', \App\Http\Controllers\BlogController::class);

Route::resource('photo', \App\Http\Controllers\PhotoController::class);
