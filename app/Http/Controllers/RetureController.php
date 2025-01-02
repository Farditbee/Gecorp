<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DataReture;
use App\Models\DetailKasir;
use App\Models\Kasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RetureController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Reture',
            'Tambah Reture',
        ];
    }

    public function index()
    {
        $menu = [$this->title[0]];
        $reture = DataReture::all();
        return view('reture.index', compact('menu', 'reture'));
    }

    public function create()
    {
        $menu = [$this->title[1], $this->label[3]];
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

                    $diskon = $detailKasir->diskon ?? 0;

                    // Format data untuk dikirim ke FE
                    $data = [
                        "error" => false,
                        "message" => "Successfully",
                        "status_code" => 200,
                        "data" => [
                            "no_nota" => $kasir->no_nota ?? null,
                            "nama_toko" => $kasir->toko ? $kasir->toko->nama_toko : "Tidak Ditemukan",
                            "id_transaksi" => $detailKasir->id_kasir,
                            "id_barang" => $barang ? $barang->id : null,
                            "tipe_transaksi" => "Kasir",
                            "nama_member" => $kasir->member ? $kasir->member->nama_member : "Guest",
                            "harga_jual" => $detailKasir->harga - $diskon,
                            "nama_barang" => $barang ? $barang->nama_barang : "Tidak Ditemukan",
                            "qty" => $detailKasir->qty,
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
            Log::error($th->getMessage());

            return response()->json([
                "error" => true,
                "message" => "Terjadi kesalahan pada server",
                "status_code" => 500,
            ], 500);
        }
    }

    public function store_nota(Request $request)
    {
        $request->validate([
            'no_nota' => 'required|string',
            'tgl_retur' => 'required|date',
        ]);

        $user = Auth::user();

        try {
            $retur = DataReture::create([
                'id_users' => $user->id,
                'id_toko' => $user->id_toko,
                'no_nota' => $request->no_nota,
                'tgl_retur' => $request->tgl_retur,
            ]);

            // Return JSON response
            return response()->json([
                'error' => false,
                'message' => 'Successfully',
                'status_code' => 200,
                'data' => [
                    'id_retur'=> $retur->id,
                    'no_nota' => $retur->no_nota,
                    'tgl_retur' => $retur->tgl_retur,
                ],
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                "error" => true,
                "message" => "Terjadi kesalahan pada server",
                "status_code" => 500,
            ], 500);
        }
    }

    public function store_temp_item(Request $request)
    {
        $request->validate([
            'no_nota' => 'required|string',
            'id_transaksi' => 'required|string',
            'id_barang' => 'required|integer',
            'qty' => 'required|integer',
            'harga_jual' => 'required|integer',
        ]);

        $user = Auth::user();

        try {
            DB::beginTransaction();

            DB::table('temp_detail_retur')->insert([
                'id_users' => $user->id,
                'id_retur' => $request->input('id_retur'),
                'id_transaksi' => $request->input('id_transaksi'),
                'id_barang' => $request->input('id_barang'),
                'no_nota' => $request->input('no_nota'),
                'qty' => $request->input('qty'),
                'harga' => $request->input('harga_jual'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json(['message' => 'Data berhasil disimpan sementara!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing temporary item: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menyimpan data sementara.',
                'status_code' => 500,
            ], 500);
        }
    }

    public function getTemporaryItems($idReture)
    {
        try {
            $items = DB::table('temp_detail_retur')
                ->where('id_users', Auth::user()->id)
                ->where('id_retur', $idReture)
                ->get();

            return response()->json([
                'error' => false,
                'message' => 'Successfully',
                'status_code' => 200,
                'data' => $items,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching temporary items: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat mengambil data sementara.',
                'status_code' => 500,
            ], 500);
        }
    }

    public function getTempoData()
    {
        try {
            $items = DataReture::all();

            return response()->json([
                'error' => false,
                'message' => 'Successfully',
                'status_code' => 200,
                'data' => $items,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching tempo data: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat mengambil data.',
                'status_code' => 500,
            ], 500);
        }
    }

    public function saveTemporaryItems(Request $request)
    {
        $request->validate([
            'id_retur' => 'required|integer',
            'no_nota' => 'required|integer',
            'id_transaksi' => 'required|array',
            'id_barang' => 'required|array',
            'qty' => 'required|array',
            'harga' => 'required|array',
        ]);

        $userId = Auth::user()->id;
        $idRetur = $request->id_retur;
        $noNota = $request->no_nota;
        $idTransaksi = $request->id_transaksi;
        $idBarang = $request->id_barang;
        $qty = $request->qty;
        $harga = $request->harga;

        try {
            DB::beginTransaction();

            foreach ($idTransaksi as $index => $idTrans) {
                DB::table('detail_retur')->insert([
                    'id_users' => $userId,
                    'id_retur' => $idRetur,
                    'id_transaksi' => $idTrans,
                    'id_barang' => $idBarang[$index],
                    'no_nota' => $noNota,
                    'qty' => $qty[$index],
                    'harga' => $harga[$index],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('temp_detail_retur')
                ->where('id_users', $userId)
                ->where('id_retur', $idRetur)
                ->delete();

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Data berhasil disimpan permanen!',
                'status_code' => 200,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving temporary items: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'status_code' => 500,
            ], 500);
        }
    }

}
