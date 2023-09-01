<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\ShareController;
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

Route::get('/account/get', [UserController::class, 'get_account_details'])->middleware(['token']);
Route::post('/account/update/email', [UserController::class, 'update_email'])->middleware('token', 'args:email');
Route::post('/account/update/username', [UserController::class, 'update_username'])->middleware(['token', 'verified', 'args:username']);
Route::post('/account/update/password', [UserController::class, 'update_password'])->middleware(['token', 'verified', 'args:password']);

Route::post('/admin/cards/create', [AdminController::class, 'create_card'])->middleware(['token', 'verified', 'admin', 'args:name,description,rarity,code,birth,death,img,country,continent,category']);
Route::post('/admin/cards/delete/{card}', [AdminController::class, 'delete_card'])->middleware(['token', 'verified', 'admin']);

Route::get('/list/all', [CardController::class, 'list_all']);
Route::get('/collection', [CardController::class, 'get_collection'])->middleware('token');
Route::get('/entities/{card_id}', [CardController::class, 'get_entities_of_card'])->middleware('token');
Route::get('/categories', [CardController::class, 'get_categories'])->middleware('token');
Route::get('/collection/filter/category/{category_id}', [CardController::class, 'get_collection_filter_by_category'])->middleware('token');

Route::get('/reward/status', [RewardController::class, 'status'])->middleware('token');
Route::post('/reward/open', [RewardController::class, 'open'])->middleware(['token', 'verified']);
Route::get('/card/share/status/{card_id}', [ShareController::class, 'status'])->middleware(['token']);
Route::post('/card/share/enable/{entity_id}', [ShareController::class, 'enable'])->middleware(['token', 'verified']);
Route::post('/card/share/disable/{entity_id}', [ShareController::class, 'disable'])->middleware(['token', 'verified']);
Route::post('/card/share/activate/{share_code}', [ShareController::class, 'activate'])->middleware(['token', 'verified']);

Route::post('/account/delete/initiate', [UserController::class, 'initiate_deletion'])->middleware(['token', 'verified']);
Route::post('/account/delete/validate', [UserController::class, 'validate_deletion'])->middleware(['token', 'args:token']);
