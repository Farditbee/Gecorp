<?php

namespace App\Http\Controllers\LaporanKeuangan;

use App\Http\Controllers\Controller;
use App\Models\Kasir;
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

    public function transaksi_kasir(Request $request)
    {
        try {
            $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
            $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;
            
            // Ambil data dari model Kasir beserta relasi toko dan users
            $query = Kasir::with('toko', 'users')->orderBy('id', $meta['orderBy']);
            
            // Filter berdasarkan bulan dan tahun dari request
            if ($request->has('month') && $request->has('year')) {
                $query->whereMonth('tgl_transaksi', $request->month)
                    ->whereYear('tgl_transaksi', $request->year);
            }
            
            // Ambil semua data setelah filter diterapkan
            $kasirList = $query->get();

            if ($kasirList->isEmpty()) {
                return response()->json([
                    'status_code' => 404,
                    'errors' => true,
                    'message' => 'Data tidak ditemukan',
                    'data' => [],
                    'data_total' => null,
                ], 404);
            }
            
            // Kelompokkan data berdasarkan tanggal (tanpa jam-menit-detik) dan id_toko
            $groupedData = $kasirList->groupBy(fn($kasir) => Carbon::parse($kasir->created_at)->toDateString() . '_' . $kasir->toko->id);
            
            // Format ulang data yang telah dikelompokkan
            $data = $groupedData->map(function ($group) {
                $first = $group->first(); // Ambil data pertama dari grup
            
                return [
                    'id' => $first->id, // ID dari transaksi pertama dalam kelompok
                    'tgl' => Carbon::parse($first->created_at)->toDateString(), // Ambil tanggal tanpa waktu
                    'subjek' => "Toko {$first->toko->singkatan}",
                    'kategori' => "Pendapatan Umum",
                    'item' => "Pendapatan Harian",
                    'jml' => 1,
                    'sat' => "Ls",
                    'hst' => $group->sum('total_nilai'), // Harga satuan total
                    'nilai_transaksi' => $group->sum('total_nilai'),
                    'kas_kecil_in' => $group->sum('total_nilai'),
                    'kas_kecil_out' => 0,
                    'kas_besar_in' => 0,
                    'kas_besar_out' => 0,
                    'piutang_in' => 0,
                    'piutang_out' => 0,
                    'hutang_in' => 0,
                    'hutang_out' => 0,
                ];
            })->values(); // Reset index array
            
            // Hitung total untuk data_total
            $kas_kecil_in = $data->sum('kas_kecil_in');
            $kas_kecil_out = 0;
            $saldo_berjalan = $kas_kecil_in - $kas_kecil_out;
            $saldo_awal = 0;
            $saldo_akhir = $saldo_berjalan - $saldo_awal;
            
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
                    'saldo_awal' => 0,
                    'saldo_akhir' => 0,
                    'saldo_berjalan' => 0,
                    'piutang_in' => 0,
                    'piutang_out' => 0,
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
                'errors' => true,
                'message' => $th->getMessage(),
                'status_code' => 500,
            ]);
        }
    }

    
}
