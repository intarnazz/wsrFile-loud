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
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\File\FileController;

// Juicy chicken (1).doc

Route::post("/registration", [UserController::class, 'reg']);
Route::post("/login", [UserController::class, 'login']);
Route::post("/logout", [UserController::class, 'logout']);
Route::post("/addFile", [FileController::class, 'add']);
Route::patch("/addFile/{file_id}", [FileController::class, 'change']);