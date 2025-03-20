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

    public function transaksi_kasir()
    {
        try {
            // Ambil data dari model Kasir beserta relasi toko dan users
            $kasirList = Kasir::with('toko', 'users')->get();
    
            // Kelompokkan data berdasarkan tanggal (tanpa jam-menit-detik) dan id_toko
            $groupedData = $kasirList->groupBy(fn($kasir) => Carbon::parse($kasir->tgl_transaksi)->toDateString() . '_' . $kasir->toko->id);
    
            // Format ulang data yang telah dikelompokkan
            $data = $groupedData->map(function ($group) {
                $first = $group->first(); // Ambil data pertama dari grup
    
                return [
                    'id' => $first->id, // ID dari transaksi pertama dalam kelompok
                    'tgl' => Carbon::parse($first->tgl_transaksi)->toDateString(), // Ambil tanggal tanpa waktu
                    'subjek' => "Toko {$first->toko->singkatan}",
                    'kategori' => "Pendapatan Umum",
                    'item' => "Pendapatan Harian Toko {$first->toko->nama_toko}",
                    'jml' => $group->sum('total_item'), // Jumlah item dijumlahkan
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
                        [
                            'saldo_awal' => $saldo_awal,
                            'saldo_akhir' => $saldo_akhir,
                            'saldo_berjalan' => $saldo_berjalan,
                            'kas_kecil_in' => $kas_kecil_in,
                            'kas_kecil_out' => $kas_kecil_out,
                        ]
                    ],
                    'kas_besar' => [
                        [
                            'saldo_awal' => 0,
                            'saldo_akhir' => 0,
                            'saldo_berjalan' => 0,
                            'kas_besar_in' => 0,
                            'kas_besar_out' => 0,
                        ]
                    ],
                    'piutang' => [
                        [
                            'saldo_awal' => 0,
                            'saldo_akhir' => 0,
                            'saldo_berjalan' => 0,
                            'piutang_in' => 0,
                            'piutang_out' => 0,
                        ]
                    ],
                    'hutang' => [
                        [
                            'saldo_awal' => 0,
                            'saldo_akhir' => 0,
                            'saldo_berjalan' => 0,
                            'hutang_in' => 0,
                            'hutang_out' => 0,
                        ]
                    ],
            ];
    
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'status_code' => 200,
                'data' => $data,
                'data_total' => $data_total,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'status_code' => 500,
            ]);
        }
    }

    
}
