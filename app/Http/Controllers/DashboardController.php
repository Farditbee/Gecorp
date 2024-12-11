<?php

namespace App\Http\Controllers;

use App\Models\DetailKasir;
use App\Models\Kasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('master.index');
    }

    public function laporan_kasir(Request $request)
    {
        $nama_toko = $request->input('nama_toko');
        $period = $request->input('period'); // ex: 'daily', 'monthly', 'yearly'
        $month = $request->input('month');
        $year = $request->input('year');

        $query = Kasir::query()->with('toko');

        if ($nama_toko && $nama_toko !== 'all') {
            $query->whereHas('toko', function ($q) use ($nama_toko) {
                $q->where('nama_toko', $nama_toko);
            });
        }

        if ($year) {
            $query->whereYear('tgl_transaksi', $year);
        }

        if ($month && $period === 'daily') {
            $query->whereMonth('tgl_transaksi', $month);
        }

        $data = $query->get()->groupBy(function ($item) use ($period) {
            if ($period === 'daily') {
                return $item->tgl_transaksi->format('Y-m-d');
            } elseif ($period === 'monthly') {
                return $item->tgl_transaksi->format('Y-m');
            } elseif ($period === 'yearly') {
                return $item->tgl_transaksi->format('Y');
            }
        });

        // Formatkan data untuk JSON response
        $formattedData = [
            'daily' => [],
            'monthly' => [],
            'yearly' => [],
        ];

        if ($period === 'daily') {
            foreach ($data as $date => $items) {
                $year = (int) substr($date, 0, 4);
                $month = (int) substr($date, 5, 2);
                $day = (int) substr($date, 8, 2);

                $formattedData['daily'][$year][$month][$day] = $items->count();
            }
        } elseif ($period === 'monthly') {
            foreach ($data as $monthYear => $items) {
                $year = (int) substr($monthYear, 0, 4);
                $month = (int) substr($monthYear, 5, 2);

                $formattedData['monthly'][$year][$month] = $items->count();
            }
        } elseif ($period === 'yearly') {
            foreach ($data as $year => $items) {
                $formattedData['yearly'][$year] = $items->count();
            }
        }

        return response()->json([
            'error' => false,
            'message' => 'Successfully',
            'status_code' => 200,
            'data' => $formattedData,
        ]);
    }

    public function getBarangJual(Request $request)
    {
        $selectedTokoIds = $request->input('id_toko', []); // Ambil toko dari request
        $query = DetailKasir::select(
            'detail_kasir.id_barang',
            'barang.nama_barang',
            DB::raw('SUM(detail_kasir.qty) as total_terjual')
        )
            ->join('barang', 'detail_kasir.id_barang', '=', 'barang.id');

        if (!empty($selectedTokoIds)) {
            $query->join('kasir', 'detail_kasir.id_kasir', '=', 'kasir.id')
                ->whereIn('kasir.id_toko', $selectedTokoIds)
                ->groupBy('kasir.id_toko', 'detail_kasir.id_barang', 'barang.nama_barang'); // Tambahkan semua kolom
        } else {
            $query->groupBy('detail_kasir.id_barang', 'barang.nama_barang'); // Tambahkan nama_barang
        }

        $dataBarang = $query->orderBy('total_terjual', 'desc')->limit(5)->get();

        // Format data menjadi array yang sesuai
        $data = $dataBarang->map(function ($item) {
            return [
                'nama_barang' => $item->nama_barang,
                'jumlah' => $item->total_terjual
            ];
        });

        return response()->json([
            "error" => false,
            "message" => $data->isEmpty() ? "No data found" : "Data retrieved successfully",
            "status_code" => 200,
            "data" => $data
        ]);
    }
}
