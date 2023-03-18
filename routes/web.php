<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AssetController;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\BeritaAcaraController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Web\DisposalAssetController;
use App\Http\Controllers\Web\MutasiAssetController;

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
    Route::post('/asset/import', [AssetController::class, 'import'])->name('asset.import');
    Route::get('/download_asset_sample', [AssetController::class, 'download'])->name('download_asset_sample');
    Route::get('/download_img', [AssetController::class, 'download_img'])->name('download_img');
    Route::post('/asset/update_photo', [AssetController::class, 'update_photo'])->name('asset.update_photo');
    Route::post('/asset/deleted_photo_asset', [AssetController::class, 'deleted_photo_asset'])->name('asset.deleted_photo_asset');
    Route::get('/asset/get_name_file', [AssetController::class, 'get_name_file'])->name('asset.get_name_file');

    Route::get('/disposal_asset', [DisposalAssetController::class, 'index'])->name('disposal_asset');
    // Route::get('/disposal_asset/data', [AssetController::class, 'getData'])->name('disposal_asset.data');
    Route::post('/disposal_asset/get_data_asset', [DisposalAssetController::class, 'getDataAsset'])->name('disposal_asset.get_data_asset');
    // Route::post('/disposal_asset/get_asset', [AssetController::class, 'getAsset'])->name('disposal_asset.update_asset');
    Route::get('/disposal_asset/get_detail_mutation/{id}', [DisposalAssetController::class, 'getDataDetailMutations'])->name('disposal_asset.get_detail_mutation');
    Route::get('/disposal_asset/ba_disposal_asset/{id}', [DisposalAssetController::class, 'ba_disposal_asset'])->name('disposal_asset.ba_disposal_asset');


    Route::get('/mutation_asset', [MutasiAssetController::class, 'index'])->name('mutation_asset');
    // Route::get('/mutation_asset/data', [AssetController::class, 'getData'])->name('mutation_asset.data');
    Route::post('/mutation_asset/get_data_asset', [MutasiAssetController::class, 'getDataAsset'])->name('mutation_asset.get_data_asset');
    // Route::post('/mutation_asset/get_asset', [AssetController::class, 'getAsset'])->name('mutation_asset.update_asset');
    Route::get('/mutation_asset/get_detail_mutation/{id}', [MutasiAssetController::class, 'getDataDetailMutations'])->name('mutation_asset.get_detail_mutation');
    Route::get('/mutation_asset/ba_mutation_asset/{id}', [MutasiAssetController::class, 'ba_mutation_asset'])->name('mutation_asset.ba_mutation_asset');
    
    Route::get('/berita_acara/tinjauan_asset/{id}/{datefrom}/{dateto}', [AssetController::class, 'tinjauan_asset']);
    Route::get('/berita_acara', [BeritaAcaraController::class, 'index'])->name('berita_acara');
    Route::get('/berita_acara/disposal_asset', [BeritaAcaraController::class, 'disposal_asset']);
    Route::get('/berita_acara/mutasi_asset', [BeritaAcaraController::class, 'mutasi_asset']);

    
    Route::get('/asset/noImage', [AssetController::class, 'noImage']);

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