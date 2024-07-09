<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MessageController;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/users', function () {
    $users = User::select('id', 'name', 'email')
        ->where('id', '!=', Auth::id()) // Aktif kullanıcıyı hariç tut
        ->get();
    return response()->json($users);
});

Route::get('/user/{id}', function ($id) {
    return User::findOrFail($id);
});

Route::post('/messages', [MessageController::class, 'getMessages']);


Route::post('/login',[LoginController::class,'login']);
