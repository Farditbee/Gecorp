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

            foreach ($jenisPengeluaran as $index => $jenis) {
                $pengeluaranList = Pengeluaran::where('id_jenis_pengeluaran', $jenis->id)
                    ->whereMonth('tanggal', $month)
                    ->whereYear('tanggal', $year)
                    ->with('detailPengeluaran') // supaya tidak N+1
                    ->get();
            
                $totalNilai = 0;
            
                foreach ($pengeluaranList as $pengeluaran) {
                    $totalDetail = $pengeluaran->detailPengeluaran->sum('nilai');
            
                    if ($pengeluaran->is_hutang == 2) {
                        // Jika is_hutang = 2 → ambil total dari detail_pengeluaran
                        $totalNilai += $totalDetail;
                    } elseif ($pengeluaran->is_hutang == 1) {
                        // Jika is_hutang = 1 → hanya hitung jika punya detail
                        if ($totalDetail > 0) {
                            $totalNilai += $totalDetail;
                        }
                    } else {
                        // Jika bukan hutang (0/null), hitung nilai - detail
                        $sisa = max(0, $pengeluaran->nilai - $totalDetail);
                        $totalNilai += $sisa;
                    }
                }
            
                $totalBeban += $totalNilai;
                $bebanOperasional[] = [
                    '3.' . ($index + 1) . ' ' . $jenis->nama_jenis,
                    number_format($totalNilai, 0, ',', '.')
                ];
            }            
                
            // Add biaya lain-lain (debt expenses) - removed from total as it's already counted in jenisPengeluaran
            $biayaLainLain = Pengeluaran::where('is_hutang', '!=', '1')
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->sum('nilai');

            // // Calculate Biaya Pembayaran Pinjaman from detail_pemasukan
            // $biayaPembayaranPinjaman = DB::table('detail_pemasukan')
            //     ->join('pemasukan', 'detail_pemasukan.id_pemasukan', '=', 'pemasukan.id')
            //     ->whereMonth('detail_pemasukan.created_at', $month)
            //     ->whereYear('detail_pemasukan.created_at', $year)
            //     ->sum('detail_pemasukan.nilai');

            // $totalBeban = $biayaLainLain;

            // Add total operational expenses
            $bebanOperasional[] = ['Total Beban Operasional', number_format($totalBeban, 0, ',', '.')];

            $total_labarugi = $totalPendapatan - ($totalBeban + $hpp);

            $totalLRJukey = $totalPendapatan - $hpp - $totalBeban;

            $data = [
                [
                    'I. Pendapatan',
                    [
                        ['1.1 Penjualan Umum', number_format($penjualanUmum, 0, ',', '.')],
                        ['1.2 Pendapatan Lainnya', number_format($pendapatanLainnya, 0, ',', '.')],
                        ['Total Pendapatan', number_format($totalPendapatan, 0, ',', '.')]
                    ]
                ],
                [
                    'II. HPP',
                    [
                        ['Total HPP', number_format($hpp, 0, ',', '.')]
                    ]
                ],
                [
                    'III. Biaya Pengeluaran',
                    $bebanOperasional
                ],
                [
                    'IV. Laba Rugi',
                    [
                        ['Laba Rugi Ditahan', number_format($total_labarugi, 0, ',', '.')]
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
