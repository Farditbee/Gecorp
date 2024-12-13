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
        $idToko = $request->input('nama_toko'); // Bisa berupa ID toko spesifik atau 'all'
    
        // Ambil data kasir berdasarkan filter toko
        $query = Kasir::with('toko:id,nama_toko');
        if ($idToko !== 'all') {
            $query->where('id_toko', $idToko);
        }
    
        $kasirData = $query->select('id', 'id_toko', 'created_at')->get();
    
        // Tentukan rentang tahun (5 tahun ke belakang hingga tahun sekarang)
        $currentYear = now()->year;
        $startYear = $currentYear - 4;
    
        // Struktur laporan
        $laporan = [
            'nama_toko' => $idToko === 'all' ? 'All' : ($kasirData->first()->toko->nama_toko ?? 'Unknown'),
            'daily' => [],
            'monthly' => [],
            'yearly' => [],
        ];
    
        // Inisialisasi struktur data untuk semua tahun dalam rentang
        foreach (range($startYear, $currentYear) as $year) {
            $laporan['daily'][$year] = [];
            $laporan['monthly'][$year] = array_fill(1, 12, 0);
            $laporan['yearly'][$year] = 0;
    
            foreach (range(1, 12) as $month) {
                $laporan['daily'][$year][$month] = array_fill(1, 31, 0);
            }
        }
    
        // Grupkan data berdasarkan tahun, bulan, dan hari
        $groupedByYear = $kasirData->groupBy(function ($item) {
            return $item->created_at->year;
        });
    
        foreach ($groupedByYear as $year => $yearData) {
            if ($year < $startYear || $year > $currentYear) {
                continue; // Abaikan data di luar rentang tahun yang diinginkan
            }
    
            $groupedByMonth = $yearData->groupBy(function ($item) {
                return $item->created_at->month;
            });
    
            foreach ($groupedByMonth as $month => $monthData) {
                $groupedByDay = $monthData->groupBy(function ($item) {
                    return $item->created_at->day;
                });
    
                foreach ($groupedByDay as $day => $transactions) {
                    $laporan['daily'][$year][$month][$day] += $transactions->count();
                }
    
                $laporan['monthly'][$year][$month - 1] += $monthData->count();
            }
    
            $laporan['yearly'][$year] = array_sum($laporan['monthly'][$year]);
        }
    
        // Format ulang array daily dan monthly agar sesuai dengan output JSON
        $laporan['daily'] = collect($laporan['daily'])->map(function ($months) {
            return collect($months)->map(function ($days) {
                return array_values($days);
            })->toArray();
        })->toArray();
    
        $laporan['monthly'] = collect($laporan['monthly'])->map(function ($months) {
            return array_values($months);
        })->toArray();
    
        $laporan['yearly'] = collect($laporan['yearly'])->map(function ($total) {
            return [$total];
        })->toArray();
    
        // Return JSON response
        return response()->json([
            'error' => false,
            'message' => 'Successfully',
            'status_code' => 200,
            'data' => [$laporan],
        ]);
    }
    
    public function getBarangJual(Request $request)
    {
        $selectedTokoIds = $request->input('id_toko'); // Ambil toko dari request
        $query = DetailKasir::select(
            'detail_kasir.id_barang',
            'barang.nama_barang',
            DB::raw('SUM(detail_kasir.qty) as total_terjual')
        )
            ->join('barang', 'detail_kasir.id_barang', '=', 'barang.id');
        if (!empty($selectedTokoIds) && $selectedTokoIds !== 'all') {
            $query->join('kasir', 'detail_kasir.id_kasir', '=', 'kasir.id')
                ->where('kasir.id_toko', $selectedTokoIds)
                ->groupBy('kasir.id_toko', 'detail_kasir.id_barang', 'barang.nama_barang');
        }
        elseif ($selectedTokoIds === 'all'){
            $query->groupBy('detail_kasir.id_barang', 'barang.nama_barang');
        } else {
            $query->groupBy('detail_kasir.id_barang', 'barang.nama_barang');
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
