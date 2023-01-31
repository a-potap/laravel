<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index']);

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
Route::get('/post/{blog}', function (\App\Models\Blog $blog) {
    return view('blog.show',compact('blog'));
});

Route::resource('photo', \App\Http\Controllers\PhotoController::class);
