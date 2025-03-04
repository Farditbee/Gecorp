<?php

namespace App\Http\Controllers;

use App\Models\DetailKasir;
use App\Models\Kasir;
use App\Models\Member;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        return view('master.index', compact('title'));
    }

    public function laporan_kasir(Request $request)
    {
        // Ambil parameter request dengan nilai default
        $idToko = $request->input('nama_toko', 'all'); // Default ke 'all'
        $period = $request->input('period', 'monthly'); // Default ke 'monthly'
        $month = $period === 'daily' ? $request->input('month', now()->month) : null;
        $year = $request->input('year', now()->year); // Default ke 'all' jika period yearly

        try {
            // Query nama toko jika idToko tidak 'all'
            $namaToko = 'All';
            if ($idToko !== 'all') {
                $toko = Toko::find($idToko);
                $namaToko = $toko ? $toko->nama_toko : 'Unknown';
            }

            // Query data kasir berdasarkan filter toko
            $query = Kasir::with('toko:id,nama_toko');
            if ($idToko !== 'all') {
                $query->where('id_toko', $idToko);
            }

            // Filter berdasarkan tahun (dan bulan jika period = daily)
            if ($year !== 'all') {
                $query->whereYear('created_at', $year);
            }
            if ($period === 'daily' && $month) {
                $query->whereMonth('created_at', $month);
            }

            $kasirData = $query->select('id', 'id_toko', 'created_at', 'total_nilai', 'total_diskon')->get();

            // Struktur laporan
            $laporan = [
                'nama_toko' => $namaToko,
                $period => [],
                'totals' => 0,
            ];

            if ($period === 'daily') {
                // Hitung data harian
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $dailyCounts = array_fill(1, $daysInMonth, 0);
                $dailyTotals = array_fill(1, $daysInMonth, 0);

                foreach ($kasirData as $data) {
                    $day = (int)$data->created_at->format('j');
                    $dailyCounts[$day]++;
                    $dailyTotals[$day] += $data->total_nilai - $data->total_diskon;
                }

                $laporan['daily'] = [
                    $year => [
                        $month => array_values($dailyCounts),
                    ],
                ];
                $laporan['totals'] = array_sum($dailyTotals);
            } elseif ($period === 'monthly') {
                // Hitung data bulanan
                $monthlyCounts = array_fill(1, 12, 0);
                $monthlyTotals = array_fill(1, 12, 0);

                foreach ($kasirData as $data) {
                    $month = (int)$data->created_at->format('n');
                    $monthlyCounts[$month]++;
                    $monthlyTotals[$month] += $data->total_nilai - $data->total_diskon;
                }

                $laporan['monthly'] = [
                    $year => array_values($monthlyCounts),
                ];
                $laporan['totals'] = array_sum($monthlyTotals);
            } elseif ($period === 'yearly') {
                $yearlyCounts = [];
                $yearlyTotals = [];

                foreach ($kasirData as $data) {
                    $dataYear = (int)$data->created_at->format('Y');
                    if ($year === 'all' || $dataYear == $year) {
                        if (!isset($yearlyCounts[$dataYear])) {
                            $yearlyCounts[$dataYear] = 0;
                            $yearlyTotals[$dataYear] = 0;
                        }
                        $yearlyCounts[$dataYear]++;
                        $yearlyTotals[$dataYear] += $data->total_nilai - $data->total_diskon;
                    }
                }

                $laporan['yearly'] = $yearlyCounts;
                $laporan['totals'] = array_sum($yearlyTotals);
            }

            // Return JSON response
            return response()->json([
                'error' => false,
                'message' => 'Successfully',
                'status_code' => 200,
                'data' => [$laporan],
            ]);
        } catch (\Throwable $th) {
            // Return JSON response untuk error
            return response()->json([
                'error' => true,
                'message' => 'Error',
                'status_code' => 500,
                'data' => $th->getMessage(),
            ]);
        }
    }

    public function getBarangJual(Request $request)
    {
        $selectedTokoIds = $request->input('id_toko'); // Ambil toko dari request
        $query = DetailKasir::select(
            'detail_kasir.id_barang',
            'barang.nama_barang',
            DB::raw('SUM(detail_kasir.qty) as total_terjual'),
            DB::raw('SUM((detail_kasir.qty * detail_kasir.harga) - COALESCE(detail_kasir.diskon, 0)) as total_nilai') // Hitung total nilai
        )
            ->join('barang', 'detail_kasir.id_barang', '=', 'barang.id');

        if (!empty($selectedTokoIds) && $selectedTokoIds !== 'all') {
            $query->join('kasir', 'detail_kasir.id_kasir', '=', 'kasir.id')
                ->where('kasir.id_toko', $selectedTokoIds)
                ->groupBy('kasir.id_toko', 'detail_kasir.id_barang', 'barang.nama_barang');
        } elseif ($selectedTokoIds === 'all') {
            $query->groupBy('detail_kasir.id_barang', 'barang.nama_barang');
        } else {
            $query->groupBy('detail_kasir.id_barang', 'barang.nama_barang');
        }

        $dataBarang = $query->orderBy('total_terjual', 'desc')->limit(10)->get();

        // Format data menjadi array yang sesuai
        $data = $dataBarang->map(function ($item) {
            return [
                'nama_barang' => $item->nama_barang,
                'jumlah' => $item->total_terjual,
                'total_nilai' => $item->total_nilai, // Tambahkan total nilai ke hasil
            ];
        });

        return response()->json([
            "error" => false,
            "message" => $data->isEmpty() ? "No data found" : "Data retrieved successfully",
            "status_code" => 200,
            "data" => $data
        ]);
    }

    public function getMember(Request $request)
    {
        $selectedTokoIds = $request->input('id_toko'); // Ambil toko dari request
        $query = Member::select(
            'member.id',
            'member.nama_member',
            'kasir.id_toko',
            'toko.nama_toko', // Tambahkan nama_toko ke dalam select
            DB::raw('COUNT(detail_kasir.id_barang) as total_barang_dibeli'),
            DB::raw('SUM(detail_kasir.qty * detail_kasir.harga) as total_pembayaran')
        )
            ->join('kasir', 'member.id', '=', 'kasir.id_member')
            ->join('detail_kasir', 'kasir.id', '=', 'detail_kasir.id_kasir')
            ->join('toko', 'kasir.id_toko', '=', 'toko.id'); // Join dengan tabel toko

        if (!empty($selectedTokoIds) && $selectedTokoIds !== 'all') {
            $query->where('kasir.id_toko', $selectedTokoIds)
                ->groupBy('kasir.id_toko', 'toko.nama_toko', 'member.id', 'member.nama_member');
        } else {
            $query->groupBy('kasir.id_toko', 'toko.nama_toko', 'member.id', 'member.nama_member');
        }

        $dataMember = $query->orderBy('total_pembayaran', 'desc')->limit(10)->get();

        // Format data menjadi array yang sesuai
        $data = $dataMember->map(function ($item) {
            return [
                'nama_member' => $item->nama_member,
                'id_toko' => $item->id_toko,
                'nama_toko' => $item->toko->singkatan,
                'total_barang_dibeli' => $item->total_barang_dibeli,
                'total_pembayaran' => $item->total_pembayaran,
            ];
        });

        return response()->json([
            "error" => false,
            "message" => $data->isEmpty() ? "No data found" : "Data retrieved successfully",
            "status_code" => 200,
            "data" => $data
        ]);
    }

    public function getOmset(Request $request)
    {
        // Ambil tanggal dari request, default ke hari ini jika tidak ada input
        $startDate = $request->input('startDate', now()->toDateString());
        $endDate = $request->input('endDate', now()->toDateString());

        // Ambil id_toko dari request
        $idTokoLogin = $request->input('id_toko', 1); // Default ke 1 jika tidak ada input

        try {
            // Hitung total omset berdasarkan kondisi id_toko dari request
            $query = Toko::leftJoin('kasir', function ($join) use ($startDate, $endDate) {
                $join->on('toko.id', '=', 'kasir.id_toko')
                    ->whereBetween('kasir.tgl_transaksi', [$startDate, $endDate]); // Filter berdasarkan rentang tanggal
            })
                ->when($idTokoLogin != 1, function ($query) use ($idTokoLogin) {
                    // Jika id_toko yang diterima bukan 1, filter hanya untuk toko tersebut
                    return $query->where('toko.id', $idTokoLogin);
                })
                ->where('toko.id', '!=', 1) // Abaikan toko dengan id_toko=1
                ->selectRaw('SUM(kasir.total_nilai - kasir.total_diskon) as total_nilai');

            $omsetData = $query->first(); // Ambil hasil pertama

            // Ambil total omset dari hasil query
            $totalOmset = $omsetData->total_nilai ?? 0;

            // Hitung total laba kotor (total hpp_jual) dari tabel detail_kasir
            $labakotorquery = DetailKasir::join('kasir', 'kasir.id', '=', 'detail_kasir.id_kasir')
                ->whereBetween('kasir.tgl_transaksi', [$startDate, $endDate]) // Filter berdasarkan rentang tanggal
                ->when($idTokoLogin != 1, function ($query) use ($idTokoLogin) {
                    // Jika id_toko yang diterima bukan 1, filter hanya untuk toko tersebut
                    return $query->where('kasir.id_toko', $idTokoLogin);
                })
                ->where('kasir.id_toko', '!=', 1) // Abaikan toko dengan id_toko=1
                ->sum('detail_kasir.hpp_jual');

            $laba_kotor = $labakotorquery ?? 0;

            // Return response JSON
            return response()->json([
                "error" => false,
                "message" => $totalOmset > 0 ? "Data retrieved successfully" : "No data found",
                "status_code" => 200,
                "data" => [
                    'total' => $totalOmset,
                    'laba_kotor' => $laba_kotor,
                ],
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

    public function getKomparasiToko(Request $request)
    {
        // Ambil parameter filter tanggal, default ke hari ini
        $startDate = $request->input('startDate', now()->startOfDay()->toDateString());
        $endDate = $request->input('endDate', now()->endOfDay()->toDateString());

        try {
            // Ambil semua toko kecuali id_toko = 1 dan gabungkan dengan transaksi
            $query = Toko::leftJoin('kasir', function ($join) use ($startDate, $endDate) {
                $join->on('toko.id', '=', 'kasir.id_toko')
                    ->whereBetween('kasir.tgl_transaksi', [$startDate, $endDate]);
            })
                ->where('toko.id', '!=', 1) // Abaikan toko dengan id_toko = 1
                ->selectRaw('toko.id, toko.singkatan, COUNT(kasir.id) as jumlah_transaksi, SUM(kasir.total_nilai - kasir.total_diskon) as total_transaksi')
                ->groupBy('toko.id', 'toko.singkatan');

            $tokoData = $query->get();

            // Inisialisasi variabel hasil
            $result = [
                'singkatan' => [],
                'total' => 0,
            ];

            // Iterasi data untuk membangun format hasil
            foreach ($tokoData as $data) {
                $result['singkatan'][] = [
                    $data->singkatan => [
                        'jumlah_transaksi' => (int) $data->jumlah_transaksi,
                        'total_transaksi' => (float) ($data->total_transaksi ?? 0),
                    ],
                ];
                $result['total'] += $data->total_transaksi ?? 0;
            }

            // Return response JSON
            return response()->json([
                'error' => false,
                'message' => 'Successfully retrieved comparison data',
                'status_code' => 200,
                'data' => $result,
            ]);
        } catch (\Throwable $th) {
            // Return JSON response untuk error
            return response()->json([
                'error' => true,
                'message' => 'Error retrieving data',
                'status_code' => 500,
                'data' => $th->getMessage(),
            ]);
        }
    }
}
