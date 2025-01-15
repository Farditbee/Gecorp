<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetBarangController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Asset Barang',
        ];
    }

    public function getAssetBarang(Request $request)
    {
        // Ambil parameter filter tanggal, default ke hari ini
        $startDate = $request->input('startDate', now()->startOfDay()->toDateString());
        $endDate = $request->input('endDate', now()->endOfDay()->toDateString());

        try {
            // Query utama untuk data aset per toko
            $query = DB::table('detail_toko')
                ->join('toko', 'detail_toko.id_toko', '=', 'toko.id') // Join dengan tabel toko
                ->selectRaw(
                    'detail_toko.id_toko,
                toko.nama_toko,
                SUM(detail_toko.qty) as total_qty,
                SUM(detail_toko.harga) as total_harga'
                )
                ->groupBy('detail_toko.id_toko', 'toko.nama_toko');

            // Tambahkan filter berdasarkan startDate dan endDate jika ada
            if (!empty($startDate) && !empty($endDate)) {
                $query->whereBetween('detail_toko.created_at', [$startDate, $endDate]);
            }

            // Eksekusi query untuk mendapatkan data aset per toko
            $dataAsset = $query->get();

            // Hitung total qty dan total harga dari semua toko
            $totalQty = 0;
            $totalHarga = 0;

            foreach ($dataAsset as $item) {
                $totalQty += $item->total_qty ?? 0;
                $totalHarga += $item->total_harga ?? 0;
            }

            // Format hasil
            $result = [
                'per_toko' => $dataAsset,
                'total' => [
                    'total_qty_all' => $totalQty,
                    'total_harga_all' => $totalHarga,
                ],
            ];

            // Return response JSON
            return response()->json([
                "error" => false,
                "message" => !empty($dataAsset) ? "Data retrieved successfully" : "No data found",
                "status_code" => 200,
                "data" => $result,
            ]);
        } catch (\Throwable $th) {
            // Return JSON response untuk error
            return response()->json([
                "error" => true,
                "message" => "Error retrieving data",
                "status_code" => 500,
                "data" => $th->getMessage(),
            ]);
        }
    }

    public function index(Request $request)
    {
        $menu = [$this->title[0], $this->label[2]];

        return view('laporan.asetbarang.index', compact('menu'));
    }
}
