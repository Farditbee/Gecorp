<?php

namespace App\Http\Controllers;

use App\Models\DetailKasir;
use App\Models\DetailRetur;
use App\Models\Kasir;
use App\Models\Member;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        $idToko = $request->input('nama_toko', 'all');
        $period = $request->input('period', 'monthly');
        $month = $period === 'daily' ? $request->input('month', now()->month) : null;
        $year = $request->input('year', now()->year);

        try {
            $namaToko = 'All';
            if ($idToko !== 'all') {
                $toko = Toko::find($idToko);
                $namaToko = $toko ? $toko->nama_toko : 'Unknown';
            }

            $query = Kasir::with('toko:id,nama_toko');
            if ($idToko !== 'all') {
                $query->where('id_toko', $idToko);
            }

            if ($year !== 'all') {
                $query->whereYear('created_at', $year);
            }
            if ($period === 'daily' && $month) {
                $query->whereMonth('created_at', $month);
            }

            $kasirData = $query->select('id', 'id_toko', 'created_at', 'total_nilai', 'total_diskon')->get();

            // Ambil data retur per toko
            $returTotal = 0;
            if ($idToko !== 'all') {
                $returTotal = DB::table('detail_retur')
                    ->join('data_retur', 'detail_retur.id_retur', '=', 'data_retur.id')
                    ->where('data_retur.id_toko', $idToko)
                    ->sum('detail_retur.harga') ?? 0;
            } else {
                $returTotal = DB::table('detail_retur')
                    ->join('data_retur', 'detail_retur.id_retur', '=', 'data_retur.id')
                    ->sum('detail_retur.harga') ?? 0;
            }

            $laporan = [
                'nama_toko' => $namaToko,
                $period => [],
                'totals' => 0,
            ];

            if ($period === 'daily') {
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
                $laporan['totals'] = array_sum($dailyTotals) - $returTotal;
            } elseif ($period === 'monthly') {
                $monthlyCounts = array_fill(1, 12, 0);
                $monthlyTotals = array_fill(1, 12, 0);

                foreach ($kasirData as $data) {
                    $bulan = (int)$data->created_at->format('n');
                    $monthlyCounts[$bulan]++;
                    $monthlyTotals[$bulan] += $data->total_nilai - $data->total_diskon;
                }

                $laporan['monthly'] = [
                    $year => array_values($monthlyCounts),
                ];
                $laporan['totals'] = array_sum($monthlyTotals) - $returTotal;
            } elseif ($period === 'yearly') {
                $yearlyCounts = [];
                $yearlyTotals = [];

                foreach ($kasirData as $data) {
                    $dataYear = (int)$data->created_at->format('Y');
                    if (!isset($yearlyCounts[$dataYear])) {
                        $yearlyCounts[$dataYear] = 0;
                        $yearlyTotals[$dataYear] = 0;
                    }
                    $yearlyCounts[$dataYear]++;
                    $yearlyTotals[$dataYear] += $data->total_nilai - $data->total_diskon;
                }

                $laporan['yearly'] = $yearlyCounts;
                // Get total returns for the specified toko
                $returByKasir = DB::table('detail_retur')
                    ->join('data_retur', 'detail_retur.id_retur', '=', 'data_retur.id')
                    ->where('data_retur.id_toko', $idToko)
                    ->sum('detail_retur.harga');

                $laporan['totals'] = array_sum($yearlyTotals) - $returByKasir;
            }

            return response()->json([
                'error' => false,
                'message' => 'Successfully',
                'status_code' => 200,
                'data' => [$laporan],
            ]);
        } catch (\Throwable $th) {
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
        $selectedTokoIds = $request->input('id_toko');
        $query = DetailKasir::select(
            'detail_kasir.id_barang',
            'barang.nama_barang',
            DB::raw('SUM(detail_kasir.qty) as total_terjual'),
            DB::raw('SUM((detail_kasir.qty * detail_kasir.harga) - COALESCE(detail_kasir.diskon, 0)) as total_nilai')
        )
            ->join('barang', 'detail_kasir.id_barang', '=', 'barang.id')
            ->leftJoin('detail_retur', function($join) {
                $join->on('detail_kasir.id_kasir', '=', 'detail_retur.id_transaksi')
                    ->on('detail_kasir.id_barang', '=', 'detail_retur.id_barang');
            });

        if (!empty($selectedTokoIds) && $selectedTokoIds !== 'all') {
            $query->join('kasir', 'detail_kasir.id_kasir', '=', 'kasir.id')
                ->where('kasir.id_toko', $selectedTokoIds)
                ->groupBy('kasir.id_toko', 'detail_kasir.id_barang', 'barang.nama_barang');
        } else {
            $query->groupBy('detail_kasir.id_barang', 'barang.nama_barang');
        }

        $query->selectRaw('COALESCE(SUM(detail_retur.qty), 0) as total_retur')->where('detail_retur.status', 'success');
        $query->selectRaw('SUM(detail_kasir.qty) - COALESCE(SUM(detail_retur.qty), 0) as net_terjual');
        $query->selectRaw('SUM((detail_kasir.qty * detail_kasir.harga) - COALESCE(detail_kasir.diskon, 0)) - COALESCE(SUM(detail_retur.qty * detail_retur.harga), 0) as net_nilai');

        $dataBarang = $query->orderBy('net_terjual', 'desc')->limit(10)->get();

        $data = $dataBarang->map(function ($item) {
            return [
                'nama_barang' => $item->nama_barang,
                'jumlah' => $item->net_terjual,
                'total_nilai' => $item->net_nilai,
                'total_retur' => $item->total_retur
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
    $selectedTokoIds = $request->input('id_toko');

    // Subquery untuk retur berdasarkan id_transaksi
    $subqueryRetur = DB::table('detail_retur')
        ->select('id_transaksi', DB::raw('SUM(harga) as total_harga_retur'))
        ->groupBy('id_transaksi');

    $query = Member::select(
        'member.id',
        'member.nama_member',
        'kasir.id_toko',
        'toko.nama_toko',
        DB::raw('COUNT(detail_kasir.id_barang) as total_barang_dibeli'),
        DB::raw('SUM(detail_kasir.qty * detail_kasir.harga) as total_pembayaran'),
        DB::raw('COALESCE(SUM(detail_kasir.qty * detail_kasir.harga), 0) - COALESCE(SUM(detail_retur_sub.total_harga_retur), 0) as total_pembayaran_setelah_retur')
    )
        ->join('kasir', 'member.id', '=', 'kasir.id_member')
        ->join('detail_kasir', 'kasir.id', '=', 'detail_kasir.id_kasir')
        ->join('toko', 'kasir.id_toko', '=', 'toko.id')
        ->leftJoinSub($subqueryRetur, 'detail_retur_sub', function ($join) {
            $join->on('kasir.id', '=', 'detail_retur_sub.id_transaksi');
        });

    if (!empty($selectedTokoIds) && $selectedTokoIds !== 'all') {
        $query->where('kasir.id_toko', $selectedTokoIds)
            ->groupBy('kasir.id_toko', 'toko.nama_toko', 'member.id', 'member.nama_member');
    } else {
        $query->groupBy('kasir.id_toko', 'toko.nama_toko', 'member.id', 'member.nama_member');
    }

    $dataMember = $query->orderBy('total_pembayaran_setelah_retur', 'desc')->limit(10)->get();

    $data = $dataMember->map(function ($item) {
        return [
            'nama_member' => $item->nama_member,
            'id_toko' => $item->id_toko,
            'nama_toko' => $item->toko->singkatan,
            'total_barang_dibeli' => $item->total_barang_dibeli,
            'total_pembayaran' => $item->total_pembayaran_setelah_retur,
            'total_pembayaran_setelah_retur' => $item->total_pembayaran,
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
        $month = $request->has('month') ? $request->month : Carbon::now()->month;
        $year = $request->has('year') ? $request->year : Carbon::now()->year;

        // Ambil id_toko dari request, default ke 1
        $idTokoLogin = $request->input('id_toko', 1);

        try {
            // Hitung total omset dari tabel kasir, tergantung id_toko
            $query = Toko::leftJoin('kasir', function ($join) use ($startDate, $endDate) {
                $join->on('toko.id', '=', 'kasir.id_toko')
                    ->whereBetween('kasir.tgl_transaksi', [$startDate, $endDate]);
            })
            ->when($idTokoLogin != 1, function ($query) use ($idTokoLogin) {
                return $query->where('toko.id', $idTokoLogin);
            })
            ->when($idTokoLogin != 1, function ($query) {
                return $query->where('toko.id', '!=', 1);
            })
            ->selectRaw('SUM(kasir.total_nilai - kasir.total_diskon) as total_nilai');

            $omsetData = $query->first();
            $totalOmset = $omsetData->total_nilai ?? 0;
            $biayaRetur = DB::table('detail_retur')
                ->leftJoin('data_retur', 'detail_retur.id_retur', '=', 'data_retur.id')
                ->where('detail_retur.metode', 'Cash')
                ->whereBetween('data_retur.tgl_retur', [$startDate, $endDate])
                ->when($idTokoLogin != 1, function ($query) use ($idTokoLogin) {
                    return $query->where('data_retur.id_toko', $idTokoLogin);
                })
                ->select(DB::raw('SUM(detail_retur.harga) as total_biaya_retur'))
                ->value('total_biaya_retur') ?? 0;

            $biayaReturs = DB::table('detail_retur')
                ->leftJoin('data_retur', 'detail_retur.id_retur', '=', 'data_retur.id')
                ->where('detail_retur.metode', 'Cash')
                ->whereBetween('data_retur.tgl_retur', [$startDate, $endDate])
                ->when($idTokoLogin != 1, function ($query) use ($idTokoLogin) {
                    return $query->where('data_retur.id_toko', $idTokoLogin);
                })
                ->select(DB::raw('SUM(detail_retur.harga - detail_retur.hpp_jual) as total_biaya_retur'))
                ->value('total_biaya_retur') ?? 0;

            $fixomset = $totalOmset - $biayaRetur;

            // Hitung laba kotor: total_harga - (hpp_jual * qty)
            $labakotorquery = DetailKasir::join('kasir', 'kasir.id', '=', 'detail_kasir.id_kasir')
                ->whereBetween('kasir.tgl_transaksi', [$startDate, $endDate])
                ->when($idTokoLogin != 1, function ($query) use ($idTokoLogin) {
                    return $query->where('kasir.id_toko', $idTokoLogin);
                })
                ->where('kasir.id_toko', '!=', 1)
                ->selectRaw('SUM(detail_kasir.total_harga) as total_penjualan, SUM(detail_kasir.hpp_jual * detail_kasir.qty) as total_hpp')
                ->first();

            $laba_kotor = ($labakotorquery->total_penjualan ?? 0) - ($labakotorquery->total_hpp ?? 0);

            $today = now()->toDateString();

            $totalTransaksiHariIni = Kasir::whereBetween('tgl_transaksi', [$startDate, $endDate])
                ->where('id_toko', '!=', 1)
                ->when($idTokoLogin != 1, function ($query) use ($idTokoLogin) {
                    return $query->where('id_toko', $idTokoLogin);
                })
                ->count();

            return response()->json([
                "error" => false,
                "message" => $totalOmset > 0 ? "Data retrieved successfully" : "No data found",
                "status_code" => 200,
                "data" => [
                    'total' => $fixomset,
                    'biaya_retur' => $biayaRetur,
                    'laba_kotor' => $laba_kotor - $biayaReturs,
                    'jumlah_trx' => $totalTransaksiHariIni,
                ],
            ]);
        } catch (\Throwable $th) {
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
        $startDate = $request->input('startDate', now()->startOfDay()->toDateString());
        $endDate = $request->input('endDate', now()->endOfDay()->toDateString());

        try {
            $query = Toko::leftJoin('kasir', function ($join) use ($startDate, $endDate) {
                $join->on('toko.id', '=', 'kasir.id_toko')
                    ->whereBetween('kasir.tgl_transaksi', [$startDate, $endDate]);
            })
                ->where('toko.id', '!=', 1)
                ->selectRaw('toko.id, toko.singkatan, COUNT(kasir.id) as jumlah_transaksi, SUM(kasir.total_nilai - kasir.total_diskon) as total_transaksi')
                ->groupBy('toko.id', 'toko.singkatan');

            $tokoData = $query->get();

            $result = [
                'singkatan' => [],
                'total' => 0,
            ];

            foreach ($tokoData as $data) {
                // Hitung assetRetur hanya untuk toko ini berdasarkan id_toko di data_retur
                $assetRetur = DB::table('detail_retur')
                    ->join('data_retur', 'detail_retur.id_retur', '=', 'data_retur.id')
                    ->leftJoin('stock_barang', 'detail_retur.id_barang', '=', 'stock_barang.id_barang')
                    ->where('data_retur.id_toko', $data->id)
                    ->whereBetween('data_retur.tgl_retur', [$startDate, $endDate])
                    ->select(DB::raw('SUM(CASE WHEN detail_retur.metode = "Cash" THEN detail_retur.harga ELSE stock_barang.hpp_baru END) as total_retur'))
                    ->value('total_retur') ?? 0;

                $assetRetur = -1 * $assetRetur;

                $result['singkatan'][] = [
                    $data->singkatan => [
                        'jumlah_transaksi' => (int) $data->jumlah_transaksi,
                        'total_transaksi' => (float) (($data->total_transaksi ?? 0) + $assetRetur),
                    ],
                ];

                $result['total'] += ($data->total_transaksi + $assetRetur ?? 0);
            }

            return response()->json([
                'error' => false,
                'message' => 'Successfully retrieved comparison data',
                'status_code' => 200,
                'data' => $result,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => 'Error retrieving data',
                'status_code' => 500,
                'data' => $th->getMessage(),
            ]);
        }
    }
}
