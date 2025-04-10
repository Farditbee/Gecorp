<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Kasir;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\JenisPengeluaran;

class LabaRugiService
{
    public function hitungLabaRugi($month, $year)
    {
        $penjualanUmum = (int) Kasir::whereMonth('tgl_transaksi', $month)
            ->whereYear('tgl_transaksi', $year)
            ->sum('total_nilai');

        $pendapatanLainnya = Pemasukan::where('is_pinjam', "0")
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->sum('nilai');

        $pinjamanModal = Pemasukan::where('is_pinjam', "1")
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->sum('nilai');

        $totalPendapatan = $penjualanUmum + $pendapatanLainnya + $pinjamanModal;

        $jenisPengeluaran = JenisPengeluaran::all();
        $totalBeban = 0;

        foreach ($jenisPengeluaran as $jenis) {
            $nilai = Pengeluaran::where('id_jenis_pengeluaran', $jenis->id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->sum('nilai');
            $totalBeban += $nilai;
        }

        $biayaLainLain = Pengeluaran::where('is_hutang', '!=', '0')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->sum('nilai');

        $totalBeban += $biayaLainLain;

        $totalLabaRugi = $totalPendapatan - $totalBeban;

        return $totalLabaRugi;
    }
}