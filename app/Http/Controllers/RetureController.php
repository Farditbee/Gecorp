<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailKasir;
use App\Models\Kasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RetureController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Tambah Data Reture',
        ];
    }

    public function index()
    {
        return view('reture.index');
    }

    public function create()
    {
        $menu = [$this->title[0], $this->label[3]];
        return view('reture.create', compact('menu'));
    }

    public function getDataReture(Request $request)
    {
        $qrcode = $request->input('qrcode');

        if (empty($qrcode)) {
            return response()->json([
                "error" => true,
                "message" => "QRCode tidak boleh kosong",
                "status_code" => 400,
            ], 400);
        }

        try {
            // Cek data di tabel detail_kasir
            $detailKasir = DetailKasir::where('qrcode', $qrcode)->first();

            if ($detailKasir) {
                // Eager loading untuk menghindari banyak query
                $kasir = Kasir::with(['toko', 'member'])->find($detailKasir->id_kasir);

                if ($kasir) {
                    $barang = Barang::find($detailKasir->id_barang);

                    // Format data untuk dikirim ke FE
                    $data = [
                        "error" => false,
                        "message" => "Successfully",
                        "status_code" => 200,
                        "data" => [
                            "nama_toko" => $kasir->toko ? $kasir->toko->nama_toko : "Tidak Ditemukan",
                            "id_transaksi" => $detailKasir->id_kasir,
                            "tipe_transaksi" => "Kasir",
                            "nama_member" => $kasir->member ? $kasir->member->nama_member : "Guest",
                            "harga_jual" => $detailKasir->harga - $detailKasir->diskon,
                            "nama_barang" => $barang ? $barang->nama_barang : "Tidak Ditemukan",
                            "qty_beli" => $detailKasir->qty,
                        ],
                    ];

                    return response()->json($data, 200);
                }
            }

            // Jika data tidak ditemukan
            return response()->json([
                "error" => true,
                "message" => "Data tidak ditemukan",
                "status_code" => 404,
            ], 404);
        } catch (\Throwable $th) {
            // Tangkap error dan log untuk debugging
            Log::error($th->getMessage());

            return response()->json([
                "error" => true,
                "message" => "Terjadi kesalahan pada server",
                "status_code" => 500,
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();

        dd($data);
    }
}
