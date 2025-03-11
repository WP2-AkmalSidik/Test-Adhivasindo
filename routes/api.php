<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DataSearchController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);

    Route::apiResource('articles', ArticleController::class);

    Route::get('/search/name', [DataSearchController::class, 'searchByName']);
    Route::get('/search/nim', [DataSearchController::class, 'searchByNIM']);
    Route::get('/search/ymd', [DataSearchController::class, 'searchByYMD']);
});
