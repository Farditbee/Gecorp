<?php

namespace App\Http\Controllers\LaporanKeuangan;

use App\Http\Controllers\Controller;
use App\Models\Kasir;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\JenisPengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LabaRugiController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Laba Rugi',
        ];
    }

    public function index()
    {
        $menu = [$this->title[0], $this->label[4]];

        return view('laporankeuangan.labarugi.index', compact('menu'));
    }

    public function getlabarugi(Request $request)
    {
        try {
            // Get month and year from request if provided, otherwise use current month and year
            $month = $request->has('month') ? $request->month : Carbon::now()->month;
            $year = $request->has('year') ? $request->year : Carbon::now()->year;

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
            $bebanOperasional = [];
            $totalBeban = 0;

            foreach ($jenisPengeluaran as $index => $jenis) {
                $totalNilai = Pengeluaran::where('id_jenis_pengeluaran', $jenis->id)
                    ->whereMonth('tanggal', $month)
                    ->whereYear('tanggal', $year)
                    ->sum('nilai');

                $totalBeban += $totalNilai;
                $bebanOperasional[] = [
                    '3.' . ($index + 1) . ' ' . $jenis->nama_jenis,
                    number_format($totalNilai, 0, ',', '.')
                ];
            }

            // Calculate return cost (Biaya Retur)
            $biayaRetur = -1 * DB::table('detail_retur')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('metode', 'Barang')
                ->select(DB::raw('SUM(harga - hpp_jual) as total_biaya_retur'))
                ->value('total_biaya_retur') ?? 0;

            $totalBeban += $biayaRetur;
            $bebanOperasional[] = [
                '3.11 Biaya Retur',
                number_format($biayaRetur, 0, ',', '.')
            ];

            // Add total operational expenses
            $bebanOperasional[] = ['Total Beban Operasional', number_format($totalBeban, 0, ',', '.')];

            $total_labarugi = $totalPendapatan - $total_hpp + $biayaRetur;

            $data = [
                [
                    'I. Pendapatan',
                    [
                        ['1.1 Penjualan Umum', number_format($penjualanUmum, 0, ',', '.')],
                        ['1.2 Pendapatan Lainnya', number_format($pendapatanLainnya, 0, ',', '.')],
                        ['1.3 Pengembalian Retur', number_format($assetRetur, 0, ',', '.')],
                        ['Total Pendapatan', number_format($totalPendapatan, 0, ',', '.')]
                    ]
                ],
                [
                    'II. HPP',
                    [
                        ['2.1 HPP Penjualan', number_format(round($hpppenjualan), 0, ',', '.')],
                        ['2.2 HPP Retur', number_format(round($hppretur), 0, ',', '.')],
                        ['Total HPP', number_format(round($total_hpp), 0, ',', '.')],
                    ]
                ],
                [
                    'III. Biaya Pengeluaran',
                    $bebanOperasional
                ],
                [
                    'IV. Laba Rugi',
                    [
                        ['Laba Rugi Ditahan', number_format(round($total_labarugi), 0, ',', '.')]
                    ]
                ],
            ];

            return response()->json([
                'error' => false,
                'message' => 'Data Laba Rugi berhasil didapatkan',
                'status' => 200,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal mendapatkan data Laba Rugi: ' . $e->getMessage(),
                'status' => 500,
                'data' => null
            ]);
        }
    }
}
