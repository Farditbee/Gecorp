<?php

namespace App\Http\Controllers\LaporanKeuangan;

use App\Http\Controllers\Controller;
use App\Models\Kasir;
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
            $penjualanUmum = Kasir::sum('total_nilai');
            $pendapatanLainnya = 0; // This can be modified to include other income sources
            $totalPendapatan = $penjualanUmum + $pendapatanLainnya;

            $data = [
                [
                    'I. Pendapatan',
                    [
                        ['1.1 Penjualan Umum', $penjualanUmum],
                        ['1.2 Pendapatan Lainnya', $pendapatanLainnya]
                    ],
                    ['Total Pendapatan', $totalPendapatan]
                ]
            ];

            return response()->json([
                'error' => false,
                'message' => 'Data Laba Rugi berhasil didapatkan',
                'status' => 200,
                'data' => $data
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
