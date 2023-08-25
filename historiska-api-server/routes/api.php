<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckTokenValidity;
use Illuminate\Support\Facades\Route;

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
Route::post('/logout', [UserController::class, 'logout'])->middleware(CheckTokenValidity::class);
Route::post('/account/activate/verify/{code}', [UserController::class, 'activate']);
Route::post('/account/activate/resend', [UserController::class, 'resend'])->middleware(CheckTokenValidity::class);
Route::post('/forgot', [UserController::class, 'forgot']);
Route::post('/recover', [UserController::class, 'recover']);

Route::get('/availability/username/{username}', [UserController::class, 'username_availability']);
Route::get('/availability/email/{email}', [UserController::class, 'email_availability']);
