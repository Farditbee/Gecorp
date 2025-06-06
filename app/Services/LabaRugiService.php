<?php

namespace App\Services;

use App\Models\DetailRetur;
use Carbon\Carbon;
use App\Models\Kasir;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\JenisPengeluaran;
use Illuminate\Support\Facades\DB;

class LabaRugiService
{
    public function hitungLabaRugi($month, $year)
    {
        $penjualanUmum = (int) Kasir::whereMonth('tgl_transaksi', $month)
            ->whereYear('tgl_transaksi', $year)
            ->sum('total_nilai');

        $pendapatanLainnya = Pemasukan::where('id_jenis_pemasukan', '!=', 1)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->sum('nilai');

        $assetRetur = -1 * (
            DB::table('detail_retur')
                ->leftJoin('stock_barang', 'detail_retur.id_barang', '=', 'stock_barang.id_barang')
                ->whereMonth('detail_retur.created_at', $month)
                ->whereYear('detail_retur.created_at', $year)
                ->where('detail_retur.metode', 'Cash')
                ->where('detail_retur.status', 'success')
                ->select(DB::raw('SUM(detail_retur.harga) as total_retur'))
                ->value('total_retur') ?? 0
        );

        $totalPendapatan = $penjualanUmum + $pendapatanLainnya + $assetRetur;

        $hpppenjualan = DB::table('detail_kasir')
            ->select(DB::raw('SUM(qty * hpp_jual) as total_hpp'))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->value('total_hpp');

        $hppretur = -1 * DB::table('detail_retur')
            ->where('detail_retur.metode', 'Cash')
            ->select(DB::raw('SUM(hpp_jual) as total_hpp'))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->value('total_hpp');

        $total_hpp = $hpppenjualan + $hppretur;

        // Get all expense types except id 11
        $jenisPengeluaran = JenisPengeluaran::where('id', '!=', 11)->get();
        $totalBeban = 0;

        foreach ($jenisPengeluaran as $index => $jenis) {
            $totalNilai = Pengeluaran::where('id_jenis_pengeluaran', $jenis->id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->sum('nilai');

            $totalBeban += $totalNilai;
        }

        $biayaRetur = -1 * DB::table('detail_retur')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('metode', 'Barang')
            ->select(DB::raw('SUM(harga - hpp_jual) as total_biaya_retur'))
            ->value('total_biaya_retur') ?? 0;

        $totalBeban += $biayaRetur;

        $totalLRJukey = $totalPendapatan - $total_hpp + $biayaRetur;

        return round($totalLRJukey);
    }
}
