<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MutationsController;
use App\Http\Controllers\CountController;
use App\Http\Controllers\CategoryAssetController;


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
Route::get('/asset', [AssetController::class, 'asset_id'])->middleware('jwt.verify');
Route::get('/assetall', [AssetController::class, 'assetall'])->middleware('jwt.verify');
Route::get('/asset/assetbydepartement', [AssetController::class, 'assetbydepartement'])->middleware('jwt.verify');
Route::post('/asset/store', [AssetController::class, 'store'])->middleware('jwt.verify');
Route::post('/asset/destroy', [AssetController::class, 'destroy'])->middleware('jwt.verify');
Route::post('/asset/update/{id}', [AssetController::class, 'update'])->middleware('jwt.verify');

Route::get('/asset/upload_image', [AssetController::class, 'get_upload_image'])->middleware('jwt.verify');
Route::post('/asset/upload_image', [AssetController::class, 'upload_image'])->middleware('jwt.verify');
Route::post('/asset/upload_update_image', [AssetController::class, 'upload_update_image'])->middleware('jwt.verify');

Route::post('/mutation', [MutationsController::class, 'insertMutation'])->middleware('jwt.verify');

Route::get('/home/asset', [CountController::class, 'asset'])->middleware('jwt.verify');
Route::get('/home/latestdata', [CountController::class, 'latestData'])->middleware('jwt.verify');

// Route::get('/category_asset', [CategoryAssetController::class, 'getCategoryByAsset'])->middleware('jwt.verify');
Route::get('/category', [CategoryAssetController::class, 'getCategory'])->middleware('jwt.verify');


Route::post('/user/changePassword/{id}', [UserController::class, 'changePassword'])->middleware('jwt.verify');
Route::get('/user', [UserController::class, 'getAuthenticatedUser'])->middleware('jwt.verify');