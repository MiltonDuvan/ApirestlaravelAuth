<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register',[UserController::class, 'register']);
Route::post('login', [UserController::class,'login']);

Route::group(['middleware'=>["auth:sanctum"]],function(){
Route::get('user-profile',[UserController::class, 'userProfile']);
Route::get('logout',[UserController::class,'logout']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

