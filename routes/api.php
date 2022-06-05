<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\UserController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [UserController::class, 'Register']);
Route::post('/login', [UserController::class, 'LogIn']);
//with sacctum
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user-profile', [UserController::class, 'UserProfile']);
    Route::get('/logout', [UserController::class, 'LogOut']);
});