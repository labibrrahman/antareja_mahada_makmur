<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\UserController;
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

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/asset', [AssetController::class, 'asset']);

Route::get('/assetall', [AssetController::class, 'assetall'])->middleware('jwt.verify');
Route::get('/assetall/{departement_id}', [AssetController::class, 'assetall'])->middleware('jwt.verify');
Route::post('/asset/store', [AssetController::class, 'store'])->middleware('jwt.verify');
Route::get('/asset/upload_image', [AssetController::class, 'get_upload_image'])->middleware('jwt.verify');
Route::post('/asset/upload_image', [AssetController::class, 'upload_image'])->middleware('jwt.verify');


Route::get('/user', [UserController::class, 'getAuthenticatedUser'])->middleware('jwt.verify');
