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


    // User Routes
    Route::get('/users',[UserController::class,'getAllUsers']);
    Route::get('/user/{id}',[UserController::class,'getUser']);
    Route::put('/user/{id}', [UserController::class, 'updateUser']);


    //Friend routes
    Route::get('/friends', [FriendController::class, 'index']);
    Route::post('/friends', [FriendController::class, 'store']);
    Route::delete('/friends/{id}', [FriendController::class, 'destroy']);

    //Messages routes
    Route::delete('/messages/clear', [MessageController::class, 'clearChat']);
    Route::post('/messages/send',[MessageController::class,'sendMessage']);
    Route::post('/messages',[MessageController::class,'getMessages']);

    //Group routes
    Route::get('/groups', [GroupController::class, 'getAllGroupsWithMembers']);
    Route::post('/groups', [GroupController::class, 'createGroup']);
    Route::get('/groups/{group}', [GroupController::class, 'getGroup']);
    Route::put('/groups/{group}', [GroupController::class, 'updateGroup']);
    Route::delete('/groups/{group}', [GroupController::class, 'destroyGroup']);

    // Group members routes
    Route::post('/groups/{group}/members', [GroupMemberController::class, 'addMember']);
    Route::delete('/groups/{group}/members/{userId}', [GroupMemberController::class, 'removeMember']);
    Route::patch('/groups/{groupId}/members/{userId}/assign-admin', [GroupMemberController::class, 'assignAdmin']);
    Route::patch('/groups/{groupId}/members/{userId}/remove-admin', [GroupMemberController::class, 'removeAdmin']);
    Route::get('/groups/{groupId}/members', [GroupMemberController::class, 'getMembers']);


    // Group messages routes
    Route::get('/groups/{group}/messages', [GroupMessageController::class, 'getUserGroupMessages']);
    Route::post('/groups/messages', [GroupMessageController::class, 'sendGroupMessage']);






    Route::get('/logout',[LoginController::class,'logout']);
});





Route::post('/register',[LoginController::class,'register']);
Route::post('/login',[LoginController::class,'login']);

