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
        $penjualanUmum = (int)Kasir::whereMonth('tgl_transaksi', $month)
            ->whereYear('tgl_transaksi', $year)
            ->sum('total_nilai');

        $pendapatanLainnya = Pemasukan::where('id_jenis_pemasukan', '!=', 1)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->sum('nilai');

        $assetRetur = -1 * DetailRetur::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('status', 'success')
            ->selectRaw('SUM(harga - hpp_jual) as total')
            ->value('total');

        $totalPendapatan = $penjualanUmum + $pendapatanLainnya + $assetRetur;

        // Calculate HPP from pembelian_barang
        $hpp = DB::table('detail_kasir')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->select(DB::raw('SUM(qty * hpp_jual) as total_hpp'))
            ->value('total_hpp');

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

        $totalLRJukey = $totalPendapatan - $hpp - $totalBeban;

        return $totalLRJukey;
    }
}
