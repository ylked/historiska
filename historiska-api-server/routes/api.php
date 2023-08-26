<?php

use App\Http\Controllers\UserController;
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

Route::post('/login', [UserController::class, 'login'])->middleware('args:id,password');
Route::post('/register', [UserController::class, 'register'])->middleware('args:username,email,password');
Route::post('/logout', [UserController::class, 'logout'])->middleware('token');
Route::post('/account/activate/verify/{code}', [UserController::class, 'activate']);
Route::post('/account/activate/resend', [UserController::class, 'resend'])->middleware('token');
Route::post('/forgot', [UserController::class, 'forgot'])->middleware('args:id');
Route::post('/recover', [UserController::class, 'recover'])->middleware('args:token,password');

Route::get('/availability/username/{username}', [UserController::class, 'username_availability']);
Route::get('/availability/email/{email}', [UserController::class, 'email_availability']);

Route::get('/account/get', [UserController::class, 'get_account_details'])->middleware(['token', 'verified']);
Route::post('/account/update/email', [UserController::class, 'update_email'])->middleware('token', 'args:email');
Route::post('/account/update/username', [UserController::class, 'update_username'])->middleware(['token', 'verified', 'args:username']);
