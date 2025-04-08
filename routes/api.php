<?php

use App\Http\Controllers\AssetBarangController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LaporanKeuangan\ArusKasController;
use App\Http\Controllers\LaporanKeuangan\LabaRugiController;
use App\Http\Controllers\LaporanKeuangan\NeracaController;
use App\Http\Controllers\LevelHargaController;
use App\Http\Controllers\LevelUserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PembelianBarangController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PengirimanBarangController;
use App\Http\Controllers\PlanOrderController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RatingMemberController;
use App\Http\Controllers\Reture\RetureSuplierController;
use App\Http\Controllers\RetureController;
use App\Http\Controllers\StockBarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/print/{id}', [KasirController::class, 'cetakEppos']);
Route::get('/getkasirs', [KasirController::class, 'getkasirs'])->name('master.transaksi.get');

Route::get('/arusKasir', [ArusKasController::class, 'getaruskas'])->name('master.aruskas.get');
Route::get('/labarugi', [LabaRugiController::class, 'getlabarugi'])->name('master.labarugi.get');

Route::get('/get-komparasi-toko', [DashboardController::class, 'getKomparasiToko'])->name('dashboard.komparasi');
Route::get('/penjualan_kasir', [DashboardController::class, 'laporan_kasir'])->name('master.index.kasir');
Route::get('/get-barang-jual', [DashboardController::class, 'getBarangJual'])->name('dashboard.rating');
Route::get('/get-member', [DashboardController::class, 'getMember'])->name('dashboard.member');
Route::get('/get-omset', [DashboardController::class, 'getOmset'])->name('dashboard.omset');
Route::get('/get-asset', [AssetBarangController::class, 'getAssetBarang'])->name('dashboard.asset');
Route::get('/get-ratingmember', [RatingMemberController::class, 'getMember'])->name('dashboard.ratingmember');

Route::get('/getpembelianbarang', [PembelianBarangController::class, 'getpembelianbarang'])->name('master.pembelian.get');
Route::get('/gettemppembelian', [PembelianBarangController::class, 'gettemppembelian'])->name('master.temppembelian.get');
Route::delete('/hapustemp', [PembelianBarangController::class, 'hapusTemp'])->name('master.temppembelian.hapus');
Route::get('/getpengirimanbarang', [PengirimanBarangController::class, 'getpengirimanbarang'])->name('master.pengiriman.get');

Route::get('/getdatauser', [UserController::class, 'getdatauser'])->name('master.getdatauser');
Route::get('/getpengeluaran', [PengeluaranController::class, 'getpengeluaran'])->name('master.getpengeluaran');
Route::get('/getpemasukan', [PemasukanController::class, 'getpemasukan'])->name('master.getpemasukan');
Route::get('/getmutasi', [MutasiController::class, 'getmutasi'])->name('master.getmutasi');
Route::get('/gettoko', [TokoController::class, 'gettoko'])->name('master.gettoko');
Route::get('/getmember', [MemberController::class, 'getmember'])->name('master.getmember');
Route::get('/getsupplier', [SupplierController::class, 'getsupplier'])->name('master.getsupplier');
Route::get('/getjenisbarang', [JenisBarangController::class, 'getjenisbarang'])->name('master.getjenisbarang');
Route::get('/getbrand', [BrandController::class, 'getbrand'])->name('master.getbrand');
Route::get('/getleveluser', [LevelUserController::class, 'getleveluser'])->name('master.getleveluser');
Route::get('/getlevelharga', [LevelHargaController::class, 'getlevelharga'])->name('master.getlevelharga');
Route::get('/getpromo', [PromoController::class, 'getpromo'])->name('master.getpromo');
Route::get('/getbarangs', [BarangController::class, 'getbarangs'])->name('master.getbarangs');
Route::get('/getstockbarang', [StockBarangController::class, 'getstockbarang'])->name('master.getstockbarang');
Route::get('/getplanorder', [PlanOrderController::class, 'getplanorder'])->name('master.getplanorder');
Route::get('/getDataReture', [RetureController::class, 'getDataReture'])->name('master.getreture');
Route::get('/getRetureQrcode', [RetureController::class, 'getRetureQrcode'])->name('master.getretureqrcode');

Route::get('/getRetureSupplier', [RetureSuplierController::class, 'get'])->name('master.getreturesupplier');
Route::get('/detailRetureSupplier', [RetureSuplierController::class, 'detailReture'])->name('master.detailReture');

Route::get('/getBarang', [PengirimanBarangController::class, 'getHargaBarang'])->name('master.getBarangKirim');

Route::get('/neraca', [NeracaController::class, 'getNeraca'])->name('master.getNeraca');

Route::prefix('master')->as('master.')->group(function () {
    Route::get('toko', [MasterController::class, 'getToko'])->name('toko');
    Route::get('member', [MasterController::class, 'getMember'])->name('member');
    Route::get('barang', [MasterController::class, 'getBarang'])->name('barang');
    Route::get('jenis', [MasterController::class, 'getJenis'])->name('jenis');
    Route::get('jenismasuk', [MasterController::class, 'getJenismasuk'])->name('jenismasuk');
    Route::get('suplier', [MasterController::class, 'getSuplier'])->name('suplier');
    Route::get('barang-pengiriman', [MasterController::class, 'getBarangPengiriman'])->name('barangKirim');
    Route::get('barang-kasir', [MasterController::class, 'getBarangKasir'])->name('barangKasir');
    Route::get('kasbon', [MasterController::class, 'getKasbon'])->name('kasbon');
});

