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
        $startDate = $request->input('startDate'); // Ambil startDate dari request
        $endDate = $request->input('endDate'); // Ambil endDate dari request

        // Query utama untuk data per toko
        $query = DB::table('detail_toko')
            ->select(
                'detail_toko.id_toko',
                'toko.nama_toko',
                DB::raw('SUM(detail_toko.qty) as total_qty'),
                DB::raw('SUM(detail_toko.harga) as total_harga')
            )
            ->join('toko', 'detail_toko.id_toko', '=', 'toko.id') // Join dengan tabel toko
            ->groupBy('detail_toko.id_toko', 'toko.nama_toko'); // Grupkan berdasarkan id_toko dan nama_toko

        // Tambahkan filter berdasarkan startDate dan endDate jika ada
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('detail_toko.created_at', [$startDate, $endDate]);
        }

        // Eksekusi query untuk mendapatkan data per toko
        $dataAsset = $query->orderBy('total_harga', 'desc')->get();

        // Query untuk menghitung total keseluruhan
        $totalsQuery = DB::table('detail_toko')
            ->select(
                DB::raw('SUM(qty) as total_qty_all'),
                DB::raw('SUM(harga) as total_harga_all')
            );

        // Tambahkan filter berdasarkan startDate dan endDate untuk total keseluruhan
        if (!empty($startDate) && !empty($endDate)) {
            $totalsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totals = $totalsQuery->first();

        // Format data menjadi array yang sesuai
        $data = $dataAsset->map(function ($item) {
            return [
                'id_toko' => $item->id_toko,
                'nama_toko' => $item->nama_toko,
                'total_qty' => $item->total_qty,
                'total_harga' => $item->total_harga,
            ];
        });

        // Tambahkan total keseluruhan ke dalam hasils
        $data->push([
            'id_toko' => 'ALL',
            'nama_toko' => 'Total',
            'total_qty' => $totals->total_qty_all,
            'total_harga' => $totals->total_harga_all,
        ]);

        return response()->json([
            "error" => false,
            "message" => $data->isEmpty() ? "No data found" : "Data retrieved successfully",
            "status_code" => 200,
            "data" => $data
        ]);
    }

    public function index(Request $request)
    {
        $menu = [$this->title[0], $this->label[2]];

        return view('laporan.asetbarang.index', compact('menu'));
    }
}
