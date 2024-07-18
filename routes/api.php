<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\GroupMessageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::delete('/messages/clear', [MessageController::class, 'clearChat']);
    Route::post('/messages/send',[MessageController::class,'sendMessage']);
    Route::post('/messages',[MessageController::class,'getMessages']);


    Route::get('/users',[UserController::class,'getAllUsers']);
    Route::get('/user/{id}',[UserController::class,'getUser']);
    Route::put('/user/{id}', [UserController::class, 'updateUser']);



    Route::get('/friends', [FriendController::class, 'index']);
    Route::post('/friends', [FriendController::class, 'store']);
    Route::delete('/friends/{id}', [FriendController::class, 'destroy']);



    Route::get('/groups', [GroupController::class, 'index']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::get('/groups/{group}', [GroupController::class, 'show']);
    Route::put('/groups/{group}', [GroupController::class, 'update']);
    Route::delete('/groups/{group}', [GroupController::class, 'destroy']);




    Route::post('/groups/{group}/members', [GroupController::class, 'addMember']);
    Route::delete('/groups/{group}/members/{userId}', [GroupController::class, 'removeMember']);
    Route::patch('/groups/{groupId}/members/{userId}/assign-admin', [GroupController::class, 'assignAdmin']);
    Route::patch('/groups/{groupId}/members/{userId}/remove-admin', [GroupController::class, 'removeAdmin']);
    Route::get('/groups/{groupId}/members', [GroupController::class, 'members']);






    // Group messages routes
    Route::get('/groups/{group}/messages', [GroupMessageController::class, 'index']);
    Route::post('/groups/messages', [GroupMessageController::class, 'store']);






    Route::get('/logout',[LoginController::class,'logout']);
});





Route::post('/register',[LoginController::class,'register']);
Route::post('/login',[LoginController::class,'login']);

