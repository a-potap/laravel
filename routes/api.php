<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(\App\Http\Controllers\Api\NewsController::class)->group(function () {
    Route::get('/news', 'index');
    Route::get('/news/{id}', 'show');
});

Route::controller(\App\Http\Controllers\Api\BlogController::class)->group(function () {
    Route::get('/blog', 'index');
    Route::get('/blog/{id}', 'show');
});

Route::controller(\App\Http\Controllers\Api\PhotosController::class)->group(function () {
    Route::get('/photos', 'index');
    Route::get('/photos/{id}', 'show');
});

Route::controller(\App\Http\Controllers\Api\CommentsController::class)->group(function () {
    Route::get('/blog/{id}/comments', 'index');
});

Route::fallback(function(){
    return response()->json(['message' => 'Resource Not Found.'], 404);
});
