<?php

use Illuminate\Support\Facades\Route;

$languages = config('app.locales');
array_unshift($languages, '');

foreach ($languages as $language) {
    Route::prefix($language)->middleware('locale')->group(function() {
        Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index']);

        Route::get('/resume', function () {
            return view('resume');
        });

        Route::get('/music', function () {
            return view('music');
        });

        Route::get('/video', function () {
            return view('video');
        });

        Route::controller(\App\Http\Controllers\NewsController::class)->group(function () {
            Route::get('/news/{news}', 'show');
            Route::get('/news', 'index');
        });

        Route::controller(\App\Http\Controllers\BlogController::class)->group(function () {
            Route::get('/blog', 'index');
            Route::get('/post/{blog}', 'show');
            Route::post('/post/{blog}/comment', 'comment');
        });

        Route::controller(\App\Http\Controllers\PhotoController::class)->group(function () {
            Route::get('/photo/{album}', 'show');
            Route::get('/photo', 'index');
        });
    });
}
