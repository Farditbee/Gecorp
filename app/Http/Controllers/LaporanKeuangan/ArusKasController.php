<?php

namespace App\Http\Controllers\LaporanKeuangan;

use App\Http\Controllers\Controller;
use App\Models\Kasir;
use App\Models\Pengeluaran;
use App\Models\DetailPengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ArusKasController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Arus Kas',
        ];
    }

    public function index()
    {
        $menu = [$this->title[0], $this->label[4]];

        return view('laporankeuangan.aruskas.index', compact('menu'));
    }

    public function getaruskas(Request $request)
    {
        try {
            $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
            $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

            // Get current month and year if not provided in request
            $month = $request->has('month') ? $request->month : Carbon::now()->month;
            $year = $request->has('year') ? $request->year : Carbon::now()->year;

            // Get data from Pengeluaran model with its details
            $pengeluaranQuery = Pengeluaran::with(['toko', 'jenis_pengeluaran', 'detail_pengeluaran'])
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->orderBy('id', $meta['orderBy']);

            // Get data from Kasir model
            $kasirQuery = Kasir::with('toko', 'users')
                ->whereMonth('tgl_transaksi', $month)
                ->whereYear('tgl_transaksi', $year)
                ->orderBy('id', $meta['orderBy']);

            // Get filtered data
            $pengeluaranList = $pengeluaranQuery->get();
            $kasirList = $kasirQuery->get();

            if ($pengeluaranList->isEmpty() && $kasirList->isEmpty()) {
                return response()->json([
                    'status_code' => 404,
                    'errors' => true,
                    'message' => 'Data tidak ditemukan',
                    'data' => [],
                    'data_total' => null,
                ], 404);
            }

            // Group pengeluaran data
            $pengeluaranGrouped = $pengeluaranList->groupBy(fn($pengeluaran) => Carbon::parse($pengeluaran->tanggal) . '_' . $pengeluaran->toko->id);
            // Group kasir data
            $kasirGrouped = $kasirList->groupBy(fn($kasir) => Carbon::parse($kasir->created_at)->toDateString() . '_' . $kasir->toko->id);

            // Format pengeluaran data
            $pengeluaranData = $pengeluaranGrouped->map(function ($group) {
                $first = $group->first();
                $mainRow = [
                    'id' => $first->id,
                    'tgl' => Carbon::parse($first->tanggal)->format('d-m-Y'),
                    'subjek' => "Toko {$first->toko->singkatan}",
                    'kategori' => $first->jenis_pengeluaran ? $first->jenis_pengeluaran->nama_jenis : ($first->ket_hutang ?? 'Tidak Terkategori'),
                    'item' => $first->nama_pengeluaran,
                    'jml' => 1,
                    'sat' => "Ls",
                    'hst' => $group->sum('nilai'),
                    'nilai_transaksi' => $group->sum('nilai'),
                    'kas_kecil_in' => 0,
                    'kas_kecil_out' => $first->is_hutang ? 0 : $group->sum('nilai'),
                    'kas_besar_in' => 0,
                    'kas_besar_out' => 0,
                    'piutang_in' => $first->is_hutang ? $group->sum('nilai') : 0,
                    'piutang_out' => 0,
                    'hutang_in' => 0,
                    'hutang_out' => 0,
                ];

                $rows = [$mainRow];

                // Add separate row for piutang_out if it exists
                if ($first->detail_pengeluaran->isNotEmpty()) {
                    foreach ($first->detail_pengeluaran as $detail) {
                        $rows[] = [
                            'id' => $first->id,
                            'tgl' => Carbon::parse($detail->created_at)->format('d-m-Y'),
                            'subjek' => "Toko {$first->toko->singkatan}",
                            'kategori' => 'Pembayaran Piutang',
                            'item' => 'Pembayaran ' . $first->nama_pengeluaran,
                            'jml' => 1,
                            'sat' => "Ls",
                            'hst' => $detail->nilai,
                            'nilai_transaksi' => $detail->nilai,
                            'kas_kecil_in' => 0,
                            'kas_kecil_out' => 0,
                            'kas_besar_in' => 0,
                            'kas_besar_out' => 0,
                            'piutang_in' => 0,
                            'piutang_out' => $detail->nilai,
                            'hutang_in' => 0,
                            'hutang_out' => 0,
                        ];
                    }
                }

                return $rows;
            })->flatten(1)->values();

            // Format kasir data
            $kasirData = $kasirGrouped->map(function ($group) {
                $first = $group->first();
                return [
                    'id' => $first->id,
                    'tgl' => Carbon::parse($first->created_at)->format('d-m-Y'),
                    'subjek' => "Toko {$first->toko->singkatan}",
                    'kategori' => "Pendapatan Umum",
                    'item' => "Pendapatan Harian",
                    'jml' => 1,
                    'sat' => "Ls",
                    'hst' => $group->sum('total_nilai'),
                    'nilai_transaksi' => $group->sum('total_nilai'),
                    'kas_kecil_in' => $group->sum('total_nilai'),
                    'kas_kecil_out' => 0,
                    'kas_besar_in' => 0,
                    'kas_besar_out' => 0,
                    'piutang_in' => 0,
                    'piutang_out' => $first->detail_pengeluaran ? $first->detail_pengeluaran->sum('nilai') : 0,
                    'hutang_in' => 0,
                    'hutang_out' => 0,
                ];
            })->values();

            // Merge and sort data
            $data = $pengeluaranData->concat($kasirData)->sortByDesc('tgl')->values();

            // Calculate totals
            $kas_kecil_in = $data->sum('kas_kecil_in');
            $kas_kecil_out = $data->sum('kas_kecil_out');
            $saldo_berjalan = $kas_kecil_in - $kas_kecil_out;
            $saldo_awal = 0;
            $saldo_akhir = $saldo_berjalan - $saldo_awal;

            $piutang_out = $data->sum('piutang_out');
            $piutang_in = $data->sum('piutang_in');
            $piutang_saldo_berjalan = $piutang_in - $piutang_out;
            $piutang_saldo_awal = 0;
            $piutang_saldo_akhir = $piutang_saldo_berjalan - $piutang_saldo_awal;

            $data_total = [
                'kas_kecil' => [
                    'saldo_awal' => $saldo_awal,
                    'saldo_akhir' => $saldo_akhir,
                    'saldo_berjalan' => $saldo_berjalan,
                    'kas_kecil_in' => $kas_kecil_in,
                    'kas_kecil_out' => $kas_kecil_out,
                ],
                'kas_besar' => [
                    'saldo_awal' => 0,
                    'saldo_akhir' => 0,
                    'saldo_berjalan' => 0,
                    'kas_besar_in' => 0,
                    'kas_besar_out' => 0,
                ],
                'piutang' => [
                    'saldo_awal' => $piutang_saldo_awal,
                    'saldo_akhir' => $piutang_saldo_akhir,
                    'saldo_berjalan' => $piutang_saldo_berjalan,
                    'piutang_in' => $piutang_in,
                    'piutang_out' => $piutang_out,
                ],
                'hutang' => [
                    'saldo_awal' => 0,
                    'saldo_akhir' => 0,
                    'saldo_berjalan' => 0,
                    'hutang_in' => 0,
                    'hutang_out' => 0,
                ],
            ];

            return response()->json([
                'data' => $data,
                'data_total' => $data_total,
                'status_code' => 200,
                'errors' => false,
                'message' => 'Berhasil'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'status_code' => 500,
            ]);
        }
    }


}
