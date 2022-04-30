<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::post('/signup', [UserController::class, 'signup']);
Route::post('/signin', [UserController::class, 'signin']);

Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::post('/signout', [UserController::class, 'signout']);
});