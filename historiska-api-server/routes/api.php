<?php

use App\Http\Middleware\checkTokenValidity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout'])->middleware(checkTokenValidity::class);

Route::get('/availability/username/{username}', [UserController::class, 'username_availability']);
Route::get('/availability/email/{email}', [UserController::class, 'email_availability']);
