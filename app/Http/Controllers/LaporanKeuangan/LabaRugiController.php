<?php

namespace App\Http\Controllers\LaporanKeuangan;

use App\Http\Controllers\Controller;
use App\Models\Kasir;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\JenisPengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

            $penjualanUmum = (int)Kasir::whereMonth('tgl_transaksi', $month)
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

            // Get all expense types
            $jenisPengeluaran = JenisPengeluaran::all();
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

            // Add biaya lain-lain (non-debt expenses)
            $biayaLainLain = Pengeluaran::where('is_hutang', '!=', '0')
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->sum('nilai');
            $totalBeban += $biayaLainLain;
            $bebanOperasional[] = ['3.11 Biaya Lain-lain', number_format($biayaLainLain, 0, ',', '.')];

            // Add total operational expenses
            $bebanOperasional[] = ['Total Beban Operasional', number_format($totalBeban, 0, ',', '.')];

            $data = [
                [
                    'I. Pendapatan',
                    [
                        ['1.1 Penjualan Umum', number_format($penjualanUmum, 0, ',', '.')],
                        ['1.2 Pendapatan Lainnya', number_format($pendapatanLainnya, 0, ',', '.')],
                        ['1.3 Pinjaman Modal', number_format($pinjamanModal, 0, ',', '.')],
                        ['Total Pendapatan', number_format($totalPendapatan, 0, ',', '.')]
                    ]
                ],
                [
                    'II. HPP',
                    []
                ],
                [
                    'III. Beban Operasional',
                    $bebanOperasional
                ]
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
