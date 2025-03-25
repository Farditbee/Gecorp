<?php

namespace App\Http\Controllers\LaporanKeuangan;

use App\Http\Controllers\Controller;
use App\Models\Kasir;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\JenisPengeluaran;
use Illuminate\Http\Request;

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

    public function getlabarugi()
    {
        try {
            $penjualanUmum = (int)Kasir::sum('total_nilai');
            $pendapatanLainnya = Pemasukan::where('is_pinjam', "0")->sum('nilai');
            $pinjamanModal = Pemasukan::where('is_pinjam', "1")->sum('nilai');
            $totalPendapatan = $penjualanUmum + $pendapatanLainnya + $pinjamanModal;

            // Get all expense types
            $jenisPengeluaran = JenisPengeluaran::all();
            $bebanOperasional = [];
            $totalBeban = 0;

            // Calculate expenses for each type
            foreach ($jenisPengeluaran as $index => $jenis) {
                $nilai = Pengeluaran::where('id_jenis_pengeluaran', $jenis->id)->sum('nilai');
                $totalBeban += $nilai;
                $bebanOperasional[] = ['3.' . ($index + 1) . ' ' . $jenis->nama_jenis, number_format($nilai, 0, ',', '.')];
            }

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
