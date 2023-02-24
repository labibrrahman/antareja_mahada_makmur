<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AssetController;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\BeritaAcaraController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', [HomeController::class, 'index']);
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
Route::get('register', [LoginController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::post('dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout');
    Route::get('/asset', [AssetController::class, 'index'])->name('asset');
    Route::get('/asset/data', [AssetController::class, 'getData'])->name('asset.data');
    Route::post('/asset/get_data_asset', [AssetController::class, 'getDataAsset'])->name('asset.get_data_asset');
    Route::post('/asset/store_price', [AssetController::class, 'storePrice'])->name('asset.store_price');
    Route::post('/asset/store_asset', [AssetController::class, 'store'])->name('asset.store_asset');
    Route::post('/asset/get_asset', [AssetController::class, 'getAsset'])->name('asset.update_asset');
    Route::post('/asset/update_asset', [AssetController::class, 'update'])->name('asset.update_asset');
    Route::post('/asset/deleted_asset', [AssetController::class, 'destroy'])->name('asset.deleted_asset');
    
    Route::get('/berita_acara', [BeritaAcaraController::class, 'index'])->name('berita_acara');
    Route::get('/berita_acara/tinjauan_asset', [BeritaAcaraController::class, 'tinjauan_asset'])->name('berita_acara.tinjauan_asset');

});


// Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
// Route::post('/login/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route::middleware(['auth'])->group(function () {
//     Route::prefix('admin')->group(function () {
//         Route::get('/', [HomeController::class, 'index'])->name('dashboard');
//         Route::get('/asset', [AssetController::class, 'index'])->name('asset');
//         Route::get('/asset/data', [AssetController::class, 'getData'])->name('asset.data');
//     });
// });