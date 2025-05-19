<?php

use App\Http\Controllers\AssetBarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\KasbonController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LaporanKeuangan\ArusKasController;
use App\Http\Controllers\LaporanKeuangan\LabaRugiController;
use App\Http\Controllers\LaporanKeuangan\NeracaController;
use App\Http\Controllers\LapPembelianController;
use App\Http\Controllers\LapPengirimanController;
use App\Http\Controllers\LevelHargaController;
use App\Http\Controllers\LevelUserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PembelianBarangController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\Pengembalian\PengembalianController;
use App\Http\Controllers\PengirimanBarangController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\PlanOrderController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RatingMemberController;
use App\Http\Controllers\Reture\RetureSuplierController;
use App\Http\Controllers\RetureController;
use App\Http\Controllers\StockBarangController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['tamu'])->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('post_login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard.index');
        Route::get('/dashboard', [AuthController::class, 'index'])->name('dashboard.index');

        // Brand Controller
        Route::get('/brand', [BrandController::class, 'index'])->name('master.brand.index');
        Route::get('/brand/create', [BrandController::class, 'create'])->name('master.brand.create');
        Route::post('/brand/store', [BrandController::class, 'store'])->name('master.brand.store');
        Route::get('/brand/edit/{id}', [BrandController::class, 'edit'])->name('master.brand.edit');
        Route::put('/brand/{id}', [BrandController::class, 'update'])->name('master.brand.update');
        Route::delete('/brand/delete/{id}', [BrandController::class, 'delete'])->name('master.brand.delete');

        // Jenis Barang Controller
        Route::get('/jenis_barang', [JenisBarangController::class, 'index'])->name('master.jenisbarang.index');
        Route::get('/jenis_barang/create', [JenisBarangController::class, 'create'])->name('master.jenisbarang.create');
        Route::post('/jenis_barang/store', [JenisBarangController::class, 'store'])->name('master.jenisbarang.store');
        Route::get('/jenis_barang/edit/{id}', [JenisBarangController::class, 'edit'])->name('master.jenisbarang.edit');
        Route::put('/jenis_barang{id}/update', [JenisBarangController::class, 'update'])->name('master.jenisbarang.update');
        Route::delete('/jenis_barang/delete/{id}', [JenisBarangController::class, 'delete'])->name('master.jenisbarang.delete');

        // Pembelian Barang
        Route::get('/pembelianbarang', [PembelianBarangController::class, 'index'])->name('transaksi.pembelianbarang.index');
        Route::get('/pembelianbarang/create', [PembelianBarangController::class, 'create'])->name('transaksi.pembelianbarang.create');
        Route::post('/pembelianbarang/store', [PembelianBarangController::class, 'store'])->name('transaksi.pembelianbarang.store');
        Route::post('/pembelianbarang/additem', [PembelianBarangController::class, 'addItem'])->name('transaksi.pembelianbarang.additem');
        Route::delete('/pembelianbarang/remove-item/{id}', [PembelianBarangController::class, 'removeItem']);
        Route::get('/pembelianbarang/{id}/edit', [PembelianBarangController::class, 'edit'])->name('transaksi.pembelianbarang.edit');
        Route::put('/pembelianbarang/{id}/update', [PembelianBarangController::class, 'update'])->name('transaksi.pembelianbarang.update');
        Route::delete('/pembelianbarang/{id}/delete', [PembelianBarangController::class, 'delete'])->name('transaksi.pembelianbarang.delete');
        Route::get('/get-stock/{id_barang}', [PembelianBarangController::class, 'getStock'])->name('transaksi.pembelian.getstock');
        Route::post('/pembelianbarang/update_status/{id}', [PembelianBarangController::class, 'updateStatus'])->name('transaksi.pembelianbarang.update_status');
        Route::get('/pembelian-barang/level-harga/{barangId}', [PembelianBarangController::class, 'getLevelHarga']);
        Route::post('/pembelian-barang/store-temp', [PembelianBarangController::class, 'storeTemp'])->name('transaksi.temp.pembelianbarang');
        Route::post('/import-pembelianbarang', [PembelianBarangController::class, 'import'])->name('master.pembelianbarang.import');

        // Toko Controller
        Route::get('/toko', [TokoController::class, 'index'])->name('master.toko.index');
        Route::get('/toko/create', [TokoController::class, 'create'])->name('master.toko.create');
        Route::post('/toko/store', [TokoController::class, 'store'])->name('master.toko.store');
        Route::get('/toko/edit/{id}', [TokoController::class, 'edit'])->name('master.toko.edit');
        Route::put('/toko/update/{id}', [TokoController::class, 'update'])->name('master.toko.update');
        Route::delete('/toko/delete/{id}', [TokoController::class, 'delete'])->name('master.toko.delete');
        Route::get('/toko/detail/{id}', [TokoController::class, 'detail'])->name('master.toko.detail');
        Route::get('/toko/detail/create/{id}', [TokoController::class, 'create_detail'])->name('master.toko.create_detail');
        Route::post('/toko/store_detail', [TokoController::class, 'store_detail'])->name('master.toko.store_detail');
        Route::get('/toko/{id_toko}/detail/{id_barang}/edit/{id}', [TokoController::class, 'edit_detail'])->name('master.toko.edit_detail');
        Route::put('/toko/{id_toko}/detail/{id_barang}/update', [TokoController::class, 'update_detail'])->name('master.toko.update_detail');
        Route::delete('/toko/{id_toko}/detail/{id_barang}/delete', [TokoController::class, 'delete_detail'])->name('master.toko.delete_detail');
        Route::get('/master/toko/search', [TokoController::class, 'search'])->name('master.toko.search');
        Route::get('/master/stock/searchs', [TokoController::class, 'searchs'])->name('master.stock.searchs');

        // User Controller
        Route::get('/user', [UserController::class, 'index'])->name('master.user.index');
        Route::get('/user/create', [UserController::class, 'create'])->name('master.user.create');
        Route::post('/user/store', [UserController::class, 'store'])->name('master.user.store');
        Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('master.user.edit');
        Route::put('/user/update/{id}', [UserController::class, 'update'])->name('master.user.update');
        Route::delete('/user/delete/{id}', [UserController::class, 'delete'])->name('master.user.delete');
        Route::post('/import-user', [UserController::class, 'import'])->name('master.user.import');

        // Barang Controller
        Route::get('/barang', [BarangController::class, 'index'])->name('master.barang.index');
        Route::get('/barang/create', [BarangController::class, 'create'])->name('master.barang.create');
        Route::post('/barang/store', [BarangController::class, 'store'])->name('master.barang.store');
        Route::get('/barang/edit/{id}', [BarangController::class, 'edit'])->name('master.barang.edit');
        Route::put('/barang/update/{id}', [BarangController::class, 'update'])->name('master.barang.update');
        Route::delete('/barang/delete/{id}', [BarangController::class, 'delete'])->name('master.barang.delete');
        Route::get('/get-brands-by-jenis', [BrandController::class, 'getBrandsByJenis'])->name('getBrandsByJenis');
        Route::post('/import-barang', [BarangController::class, 'import'])->name('master.barang.import');

        // Supplier Controller
        Route::get('/supplier', [SupplierController::class, 'index'])->name('master.supplier.index');
        Route::get('/supplier/create', [SupplierController::class, 'create'])->name('master.supplier.create');
        Route::post('/supplier/store', [SupplierController::class, 'store'])->name('master.supplier.store');
        Route::get('/supplier/edit/{id}', [SupplierController::class, 'edit'])->name('master.supplier.edit');
        Route::put('/supplier/update/{id}', [SupplierController::class, 'update'])->name('master.supplier.update');
        Route::delete('/supplier/delete/{id}', [SupplierController::class, 'delete'])->name('master.supplier.delete');
        Route::post('/import-supplier', [SupplierController::class, 'import'])->name('master.supplier.import');

        // Member Controller
        Route::get('/member', [MemberController::class, 'index'])->name('master.member.index');
        Route::post('/member/store', [MemberController::class, 'store'])->name('master.member.store');
        Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('members.edit');
        Route::put('/member/update/{id}', [MemberController::class, 'update'])->name('master.member.update');
        Route::delete('/member/delete/{id}', [MemberController::class, 'delete'])->name('master.member.delete');
        Route::get('/get-level-harga/{id_toko}', [MemberController::class, 'getLevelHarga']);
        Route::post('/import-member', [MemberController::class, 'import'])->name('master.member.import');

        // Promo Controller
        Route::get('/promo', [PromoController::class, 'index'])->name('master.promo.index');
        Route::post('/promo/store', [PromoController::class, 'store'])->name('master.promo.store');
        Route::put('/promo/update', [PromoController::class, 'update'])->name('master.promo.update');
        Route::put('/promo/update-status', [PromoController::class, 'updateStatus'])->name('master.promo.update-status');

        // Level Harga Controller
        Route::get('/levelharga', [LevelHargaController::class, 'index'])->name('master.levelharga.index');
        Route::get('/levelharga/create', [LevelHargaController::class, 'create'])->name('master.levelharga.create');
        Route::post('/levelharga/store', [LevelHargaController::class, 'store'])->name('master.levelharga.store');
        Route::get('/levelharga/edit/{id}', [LevelHargaController::class, 'edit'])->name('master.levelharga.edit');
        Route::put('/levelharga/update/{id}', [LevelHargaController::class, 'update'])->name('master.levelharga.update');
        Route::delete('/levelharga/delete/{id}', [LevelHargaController::class, 'delete'])->name('master.levelharga.delete');

        // Level User Controller
        Route::get('/leveluser', [LevelUserController::class, 'index'])->name('master.leveluser.index');
        Route::get('/leveluser/create', [LevelUserController::class, 'create'])->name('master.leveluser.create');
        Route::post('/leveluser/store', [LevelUserController::class, 'store'])->name('master.leveluser.store');
        Route::get('/leveluser/edit/{id}', [LevelUserController::class, 'edit'])->name('master.leveluser.edit');
        Route::put('/leveluser/update/{id}', [LevelUserController::class, 'update'])->name('master.leveluser.update');
        Route::delete('/leveluser/delete/{id}', [LevelUserController::class, 'delete'])->name('master.leveluser.delete');

        // Stock Barang Controller
        Route::get('/stockbarang', [StockBarangController::class, 'index'])->name('master.stockbarang.index');
        Route::get('/stockbarang/create', [StockBarangController::class, 'create'])->name('master.stockbarang.create');
        Route::get('/get-stock-details/{id_barang}', [StockBarangController::class, 'getStockDetails'])->name('get-stock-details');
        Route::get('/get-item/{id}', [StockBarangController::class, 'getItem'])->name('get.item');
        Route::post('/update-level-harga', [StockBarangController::class, 'updateLevelHarga'])->name('updateLevelHarga');
        Route::get('/hpp_barang', [StockBarangController::class, 'getHppBarang'])->name('master.stock.hpp_barang');
        Route::get('/get-detail-barang/{id_barang}', [StockBarangController::class, 'getdetailbarang'])->name('get.detail.barang');
        // Route::get('/stock/detail/{id}', [StockBarangController::class, 'detail'])->name('master.stock.detail');

        // Pengeluaran Controller
        Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('keuangan.pengeluaran.index');
        Route::post('/pengeluaran/store', [PengeluaranController::class, 'store'])->name('master.pengeluaran.store');
        Route::delete('/pengeluaran/delete/{id}', [PengeluaranController::class, 'delete'])->name('master.pengeluaran.delete');
        Route::put('/pengeluaran/update/{id}', [PengeluaranController::class, 'updatehutang'])->name('master.pengeluaran.update');
        Route::get('/pengeluaran/detail/{id}', [PengeluaranController::class, 'detail'])->name('master.pengeluaran.detail');

        // Pemasukan Controller
        Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('keuangan.pemasukan.index');
        Route::post('/pemasukan/store', [PemasukanController::class, 'store'])->name('master.pemasukan.store');
        Route::delete('/pemasukan/delete/{id}', [PemasukanController::class, 'delete'])->name('master.pemasukan.delete');
        Route::put('/pemasukan/update/{id}', [PemasukanController::class, 'updatepinjam'])->name('master.pemasukan.update');
        Route::get('/pemasukan/detail/{id}', [PemasukanController::class, 'detail'])->name('master.pemasukan.detail');

        // Mutasi Controller
        Route::get('/mutasi', [MutasiController::class, 'index'])->name('keuangan.mutasi.index');
        Route::post('mutasi/store', [MutasiController::class, 'store'])->name('master.mutasi.store');
        Route::delete('/mutasi/delete/{id}', [MutasiController::class, 'delete'])->name('master.mutasi.delete');

        // Hutang Controller
        Route::get('/hutang', [HutangController::class, 'index'])->name('keuangan.hutang.index');
        Route::post('hutang/store', [HutangController::class, 'store'])->name('master.hutang.store');
        Route::get('/hutang/detail/{id}', [HutangController::class, 'detail'])->name('master.hutang.detail');
        Route::put('/hutang/update/{id}', [HutangController::class, 'updatehutang'])->name('master.hutang.update');
        Route::delete('/hutang/delete/{id}', [HutangController::class, 'delete'])->name('master.hutang.delete');

        // Piutang Controller
        Route::get('/piutang', [PiutangController::class, 'index'])->name('keuangan.piutang.index');
        Route::post('piutang/store', [PiutangController::class, 'store'])->name('master.piutang.store');
        Route::get('/piutang/detail/{id}', [PiutangController::class, 'detail'])->name('master.piutang.detail');
        Route::put('/piutang/update/{id}', [PiutangController::class, 'updatepiutang'])->name('master.piutang.update');
        Route::delete('/piutang/delete/{id}', [PiutangController::class, 'delete'])->name('master.piutang.delete');

        Route::get('/stockopname', [StockOpnameController::class, 'index'])->name('master.stockopname.index');
        Route::get('/planorder', [PlanOrderController::class, 'index'])->name('distribusi.planorder.index');

        // Pengiriman Barang
        Route::get('/pengirimanbarang', [PengirimanBarangController::class, 'index'])->name('distribusi.pengirimanbarang.index');
        Route::get('/pengirimanbarang/create', [PengirimanBarangController::class, 'create'])->name('distribusi.pengirimanbarang.create');
        Route::get('/pengirimanbarang/detail/{id}', [PengirimanBarangController::class, 'detail'])->name('distribusi.pengirimanbarang.detail');
        Route::get('/get-users-by-toko/{id_toko}', [PengirimanBarangController::class, 'getUsersByToko']);
        Route::get('/get-barang-stock/{id_barang}/{id_toko}', [PengirimanBarangController::class, 'getBarangStock']);
        Route::get('/get-harga-barang/{id_barang}/{id_toko}', [PengirimanBarangController::class, 'getHargaBarang']);
        Route::post('/pengirimanbarang/additem', [PengirimanBarangController::class, 'addItem'])->name('transaksi.pengirimanbarang.additem');
        Route::delete('/pengirimanbarang/remove-item/{id}', [PengirimanBarangController::class, 'removeItem']);
        Route::post('/pengirimanbarang/store', [PengirimanBarangController::class, 'store'])->name('transaksi.pengirimanbarang.store');
        Route::post('/pengirimanbarang/storeR', [PengirimanBarangController::class, 'storeReture'])->name('transaksi.pengirimanbarang.storeReture');
        Route::post('/pengirimanbarang/storeDR', [PengirimanBarangController::class, 'storeDetailReture'])->name('transaksi.pengirimanbarang.storeDetailReture');
        Route::get('/pengirimanbarang/edit/{id}', [PengirimanBarangController::class, 'edit'])->name('transaksi.pengirimanbarang.edit');
        Route::post('/pengirimanbarang/update_status/{id}', [PengirimanBarangController::class, 'updateStatus'])->name('transaksi.pengirimanbarang.update_status');
        Route::put('/pengirimanbarang/update/{id}', [PengirimanBarangController::class, 'update'])->name('transaksi.pengirimanbarang.update');
        Route::post('/pengirimanbarang/storeTemp', [PengirimanBarangController::class, 'storetempPengiriman'])->name('temp.store.pengiriman');
        Route::delete('/pengirimanbarang/delete', [PengirimanBarangController::class, 'deleteTempPengiriman'])->name('delete.temp.pengiriman');
        Route::put('/pengirimanbarang/update', [PengirimanBarangController::class, 'updatetempPengiriman'])->name('update.temp.pengiriman');
        Route::get('/pengirimanbarang/get-temporary-items', [PengirimanBarangController::class, 'getTempPengiriman'])->name('get.temp.pengiriman');
        Route::post('/pengirimanbarang/save', [PengirimanBarangController::class, 'save'])->name('save.pengiriman');
        Route::delete('/pengirimanbarang/{id}/delete', [PengirimanBarangController::class, 'delete'])->name('transaksi.pengiriman.delete');
        Route::get('/pengirimanbarang/reture', [PengirimanBarangController::class, 'returePengiriman'])->name('distribusi.pengirimanbarang.reture');

        // Kasir Controller
        Route::get('/kasir', [KasirController::class, 'index'])->name('transaksi.kasir.index');
        Route::post('/kasir/store', [KasirController::class, 'store'])->name('transaksi.kasir.store');
        Route::get('/kasirs/{id}/detail', [KasirController::class, 'detail'])->name('kasirs.detail');
        Route::put('/kasir/update/{id}', [KasirController::class, 'update'])->name('transaksi.kasir.update');
        Route::delete('/kasir/delete/{id}', [KasirController::class, 'delete'])->name('transaksi.kasir.delete');
        Route::get('/kasir/get-filtered-harga', [KasirController::class, 'getFilteredHarga']);
        Route::get('/cetak-struk/{id_kasir}', [KasirController::class, 'cetakStruk'])->name('cetak.struk');

        Route::get('/lappembelian', [LapPembelianController::class, 'index'])->name('laporan.pembelian.index');
        Route::get('/lappengiriman', [LapPengirimanController::class, 'index'])->name('laporan.pengiriman.index');
        Route::get('/laprating', [RatingController::class, 'index'])->name('laporan.rating.index');
        Route::post('/get-barang-jual', [RatingController::class, 'getBarangJual'])->name('get-barang-jual');
        Route::get('/get-barang-jual', [RatingController::class, 'getBarangJual']);
        Route::get('/asetbarang', [AssetBarangController::class, 'index'])->name('laporan.asetbarang.index');
        Route::get('/ratingmember', [RatingMemberController::class, 'index'])->name('laporan.ratingmember.index');

        // Reture Controller
        Route::get('/reture', [RetureController::class, 'index'])->name('reture.index');
        Route::get('/reture/create', [RetureController::class, 'create'])->name('reture.create');
        Route::post('/reture/storeNota', [RetureController::class, 'store_nota'])->name('reture.storeNota');
        Route::post('/reture/updateStore', [RetureController::class, 'updateStore'])->name('reture.updateStore');
        Route::post('/reture/tempStore', [RetureController::class, 'store_temp_item'])->name('reture.tempStore');
        Route::get('/temporary-items', [RetureController::class, 'getTemporaryItems'])->name('get.temporary.items');
        Route::get('/temporary-Data', [RetureController::class, 'getTempoData'])->name('get.tempoData');
        Route::post('/reture/permStore', [RetureController::class, 'saveTemporaryItems'])->name('reture.permStore');
        Route::delete('/reture/deleteTemp', [RetureController::class, 'deleteRowTable'])->name('delete.tempData');
        Route::get('/retureItem', [RetureController::class, 'getRetureItems'])->name('get.retureItems');
        Route::post('/updateNotaReture', [RetureController::class, 'updateNotaReture'])->name('create.updateNotaReture');
        Route::post('/reture/storeNotaSupplier', [RetureController::class, 'storeNotaSupplier'])->name('create.NoteReture');
        Route::delete('/reture/deleteTempItem', [RetureController::class, 'deleteTempItem'])->name('delete.tempItem');

        // Pengembalian Barang Controller
        Route::delete('/pengembalian/delete', [PengembalianController::class, 'delete'])->name('pengembalian.delete');

        Route::prefix('reture')->as('reture.')->group(function () {
            Route::prefix('suplier')->as('suplier.')->group(function () {
                Route::get('/', [RetureSuplierController::class, 'index'])->name('index');
                Route::post('/store', [RetureSuplierController::class, 'store'])->name('store');
                Route::delete('/delete', [RetureSuplierController::class, 'delete'])->name('delete');
            });
        });

        Route::prefix('kasbon')->as('transaksi.')->group(function () {
            Route::get('/', [KasbonController::class, 'index'])->name('index');
            Route::get('/detail/{id}', [KasbonController::class, 'detail'])->name('detail');
            Route::post('/bayar', [KasbonController::class, 'bayar'])->name('bayar');
        });

        Route::prefix('laporan-keuangan')->as('laporankeuangan.')->group(function () {
            Route::prefix('arus-kas')->as('aruskas.')->group(function () {
                Route::get('/', [ArusKasController::class, 'index'])->name('index');
            });
            Route::prefix('laba-rugi')->as('labarugi.')->group(function () {
                Route::get('/', [LabaRugiController::class, 'index'])->name('index');
            });
            Route::prefix('neraca')->as('neraca.')->group(function () {
                Route::get('/', [NeracaController::class, 'index'])->name('index');
            });
        });
    });
});
