<?php

namespace App\Http\Controllers\LaporanKeuangan;

use App\Http\Controllers\Controller;
use App\Models\Kasir;
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
    
            // Format data untuk response JSON
            $data = $kasirList->map(function ($kasir) {
                return [
                    'id' => $kasir->id,
                    'tgl' => $kasir->tgl_transaksi,
                    'subjek' => "Admin Toko {$kasir->users->nama}",
                    'kategori' => "Pendapatan Umum",
                    'item' => "Pendapatan Harian Toko {$kasir->toko->nama_toko}",
                    'jml' => $kasir->total_item,
                    'sat' => "Ls",
                    'hst' => $kasir->total_nilai,
                    'nilai_transaksi' => $kasir->total_nilai,
                    'kas_kecil_in' => $kasir->total_nilai,
                    'kas_kecil_out' => 0,
                    'kas_besar_in' => 0,
                    'kas_besar_out' => 0,
                    'piutang_in' => 0,
                    'piutang_out' => 0,
                    'hutang_in' => 0,
                    'hutang_out' => 0,
                ];
            });
    
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'status_code' => 200,
                'data' => $data,
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
