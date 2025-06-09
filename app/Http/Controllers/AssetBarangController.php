<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetBarangController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Aset Barang Jualan',
        ];
    }

    public function getAssetBarang(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        try {
            // Query utama untuk data aset per toko
            $query = DB::table('detail_toko')
                ->select(
                    'detail_toko.id_toko',
                    'toko.nama_toko',
                    'toko.wilayah', // Tambahkan wilayah ke dalam query
                    DB::raw('SUM(detail_toko.qty) as total_qty'),
                    DB::raw('SUM(detail_toko.harga) as total_harga')
                )
                ->join('toko', 'detail_toko.id_toko', '=', 'toko.id')
                ->groupBy('detail_toko.id_toko', 'toko.nama_toko', 'toko.wilayah');


            // Tambahkan filter berdasarkan tanggal
            if (!empty($startDate) && !empty($endDate)) {
                $query->whereBetween('detail_toko.created_at', [$startDate, $endDate]);
            }

            // Tambahkan sorting
            $query->orderBy('total_harga', $meta['orderBy']);

            if (!empty($request['search'])) {
                $searchTerm = trim(strtolower($request['search']));

                $query->where(function ($query) use ($searchTerm) {
                    // Pencarian pada kolom langsung
                    $query->orWhereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                    $query->orWhereRaw("LOWER(wilayah) LIKE ?", ["%$searchTerm%"]);
                });
            }

            // Eksekusi query dengan pagination
            $dataAsset = $query->paginate($meta['limit']);

            // Hitung total qty dan total harga dari semua toko
            $totalsQuery = DB::table('detail_toko')
                ->selectRaw('SUM(qty) as total_qty_all, SUM(harga) as total_harga_all');

            if (!empty($startDate) && !empty($endDate)) {
                $totalsQuery->whereBetween('created_at', [$startDate, $endDate]);
            }

            $totals = $totalsQuery->first();

            // Format data untuk responses
            $mappedData = collect($dataAsset->items())->map(function ($item) {
                return [
                    'id_toko' => $item->id_toko,
                    'nama_toko' => $item->nama_toko . ' (' . $item->wilayah . ')', // Menggabungkan nama_toko dengan wilayah
                    'total_qty' => $item->total_qty,
                    'total_harga' => $item->total_harga,
                ];
            });


            // Tambahkan total keseluruhan ke dalam hasil
            $mappedData->push([
                'id_toko' => 'ALL',
                'nama_toko' => 'Total',
                'total_qty' => $totals->total_qty_all,
                'total_harga' => $totals->total_harga_all,
            ]);

            // Buat metadata pagination
            $paginationMeta = [
                'total' => $dataAsset->total(),
                'per_page' => $dataAsset->perPage(),
                'current_page' => $dataAsset->currentPage(),
                'total_pages' => $dataAsset->lastPage(),
            ];

            return response()->json([
                'data' => $mappedData,
                'status_code' => 200,
                'errors' => false,
                'message' => 'Data retrieved successfully',
                'pagination' => $paginationMeta,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => 'Error retrieving data',
                'status_code' => 500,
                'data' => $th->getMessage(),
            ]);
        }
    }

    public function index(Request $request)
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }
        $menu = [$this->title[0], $this->label[2]];

        return view('laporan.asetbarang.index', compact('menu'));
    }
}
