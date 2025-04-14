<?php

namespace App\Services;

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

        $pendapatanLainnya = Pemasukan::where('is_pinjam', "0")
            ->where('id_jenis_pemasukan', '!=', 1)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->sum('nilai');

        $pinjamanModal = Pemasukan::whereIn('is_pinjam', ["1", "2"])
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->sum('nilai');

        $totalPendapatan = $penjualanUmum + $pendapatanLainnya;

        // Calculate HPP from pembelian_barang
        $hpp = DB::table('pembelian_barang')
            ->whereMonth('tgl_nota', $month)
            ->whereYear('tgl_nota', $year)
            ->sum('total_nilai');

        // Get all expense types except id 11
        $jenisPengeluaran = JenisPengeluaran::where('id', '!=', 11)->get();
        $bebanOperasional = [];
        $totalBeban = 0;

        // Calculate expenses for each type
        foreach ($jenisPengeluaran as $index => $jenis) {
            $nilai = Pengeluaran::where('id_jenis_pengeluaran', $jenis->id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->sum('nilai');
            $totalBeban += $nilai;
            $bebanOperasional[] = ['3.' . ($index + 1) . ' ' . $jenis->nama_jenis, number_format($nilai, 0, ',', '.')];
        }

        // Add biaya lain-lain (debt expenses) - removed from total as it's already counted in jenisPengeluaran
        $biayaLainLain = Pengeluaran::where('is_hutang', '!=', '0')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->sum('nilai');

        // Calculate Biaya Pembayaran Pinjaman from detail_pemasukan
        $biayaPembayaranPinjaman = DB::table('detail_pemasukan')
            ->join('pemasukan', 'detail_pemasukan.id_pemasukan', '=', 'pemasukan.id')
            ->whereMonth('detail_pemasukan.created_at', $month)
            ->whereYear('detail_pemasukan.created_at', $year)
            ->sum('detail_pemasukan.nilai');

        $totalBeban += $biayaPembayaranPinjaman;

        $total_labarugi = $totalPendapatan - ($totalBeban + $hpp);

        $totalLRJukey = $totalPendapatan - $hpp - $totalBeban;
        
        return $total_labarugi;
    }
}