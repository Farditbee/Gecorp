<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PembelianBarangController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/print/{id}', [KasirController::class, 'cetakEppos']);

Route::get('/dashboard', [DashboardController::class, 'laporan_kasir'])->name('master.index.kasir');
Route::get('/get-barang-jual', [DashboardController::class, 'getBarangJual'])->name('dashboard.rating');

Route::get('/getpembelianbarang', [PembelianBarangController::class, 'getpembelianbarang'])->name('master.pembelian.get');
Route::get('/getdatauser', [UserController::class, 'getdatauser'])->name('master.getdatauser');
