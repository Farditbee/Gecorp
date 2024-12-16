<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LevelHargaController;
use App\Http\Controllers\LevelUserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PembelianBarangController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UserController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/print/{id}', [KasirController::class, 'cetakEppos']);

Route::get('/penjualan_kasir', [DashboardController::class, 'laporan_kasir'])->name('master.index.kasir');
Route::get('/get-barang-jual', [DashboardController::class, 'getBarangJual'])->name('dashboard.rating');

Route::get('/getpembelianbarang', [PembelianBarangController::class, 'getpembelianbarang'])->name('master.pembelian.get');

Route::get('/getdatauser', [UserController::class, 'getdatauser'])->name('master.getdatauser');
Route::get('/gettoko', [TokoController::class, 'gettoko'])->name('master.gettoko');
Route::get('/getmember', [MemberController::class, 'getmember'])->name('master.getmember');
Route::get('/getsupplier', [SupplierController::class, 'getsupplier'])->name('master.getsupplier');
Route::get('/getjenisbarang', [JenisBarangController::class, 'getjenisbarang'])->name('master.getjenisbarang');
Route::get('/getbrand', [BrandController::class, 'getbrand'])->name('master.getbrand');
Route::get('/getleveluser', [LevelUserController::class, 'getleveluser'])->name('master.getleveluser');
Route::get('/getlevelharga', [LevelHargaController::class, 'getlevelharga'])->name('master.getlevelharga');
Route::get('/getpromo', [PromoController::class, 'getpromo'])->name('master.getpromo');
