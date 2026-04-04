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

// Authentication routes
Route::controller(\App\Http\Controllers\Api\AuthController::class)->prefix('auth')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::get('/me', 'me')->middleware('auth:sanctum');
});

// Chat routes (all require auth:sanctum)
Route::middleware('auth:sanctum')->prefix('chat')->group(function () {
    Route::controller(\App\Http\Controllers\Api\ChatRoomController::class)->group(function () {
        Route::get('/rooms', 'index');
        Route::post('/rooms', 'store');
        Route::get('/rooms/{id}', 'show');
        Route::post('/rooms/private', 'getOrCreatePrivate');
        Route::post('/rooms/{id}/participants', 'addParticipants');
        Route::delete('/rooms/{id}/participants/{userId}', 'removeParticipant');
        Route::post('/rooms/{id}/read', 'markAsRead');
    });

    Route::controller(\App\Http\Controllers\Api\ChatMessageController::class)->group(function () {
        Route::get('/rooms/{roomId}/messages', 'index');
        Route::post('/rooms/{roomId}/messages', 'store')->middleware('throttle:messages');
    });
});

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
    Route::post('/blog/{blog}/comments', 'store')->middleware(['throttle:comments']);
});

Route::fallback(function(){
    return response()->json(['message' => 'Resource Not Found.'], 404);
});
