<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });




});
Route::delete('/messages/clear', [MessageController::class, 'clearChat']);
Route::post('/messages/send',[MessageController::class,'sendMessage']);
Route::post('/messages',[MessageController::class,'getMessages']);
Route::get('/users',[UserController::class,'getAllUsers']);
Route::post('/register',[LoginController::class,'register']);
Route::post('/login',[LoginController::class,'login']);
