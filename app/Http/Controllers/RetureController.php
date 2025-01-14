<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DataReture;
use App\Models\DetailKasir;
use App\Models\DetailRetur;
use App\Models\Kasir;
use App\Models\Member;
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
        $id_member = $request->input('id_member');
    
        if (empty($qrcode)) {
            return response()->json([
                "error" => true,
                "message" => "QRCode tidak boleh kosong",
                "status_code" => 400,
            ], 400);
        }
    
        try {
            $detailKasir = DetailKasir::where('qrcode', $qrcode)->first();
    
            if ($detailKasir) {
                // Eager loading untuk menghindari banyak query
                $kasir = Kasir::with(['toko', 'member'])->find($detailKasir->id_kasir);
    
                if ($kasir) {
                    if ($kasir->id_member != $id_member) {
                        return response()->json([
                            "error" => true,
                            "message" => "Barang bukan milik anda / Tidak ditemukan",
                            "status_code" => 403,
                        ], 403);
                    }
    
                    $barang = Barang::find($detailKasir->id_barang);
    
                    $diskon = $detailKasir->diskon ?? 0;
                    $reture_qty = $detailKasir->reture_qty ?? 0;

                    // Check if qty - reture_qty equals 0
                    if ($detailKasir->qty - $reture_qty == 0) {
                        return response()->json([
                            "error" => true,
                            "message" => "Sudah tidak ada barang yang bisa di Reture",
                            "status_code" => 400,
                        ], 400);
                    }
                    
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
                            "harga" => $detailKasir->harga - $diskon,
                            "nama_barang" => $barang ? $barang->nama_barang : "Tidak Ditemukan",
                            "qty" => $detailKasir->qty - $reture_qty,
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
            'id_member' => 'required|string',
        ]);

        $user = Auth::user();

        try {
            $retur = DataReture::create([
                'id_users' => $user->id,
                'id_toko' => $user->id_toko,
                'no_nota' => $request->no_nota,
                'tgl_retur' => $request->tgl_retur,
                'id_member' => $request->id_member,
            ]);

            $member = Member::find($request->id_member);

            // Return JSON response
            return response()->json([
                'error' => false,
                'message' => 'Successfully',
                'status_code' => 200,
                'data' => [
                    'id_retur'=> $retur->id,
                    'no_nota' => $retur->no_nota,
                    'tgl_retur' => $retur->tgl_retur,
                    'id_member' => $retur->id_member,
                    'nama_member' => $member->nama_member,
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
            'harga' => 'required|integer',
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
                'harga' => $request->input('harga'),
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

    public function getTemporaryItems(Request $request)
    {
        $request->validate([
            'id_retur' => 'required|integer',
        ]);

        $idReture = $request->id_retur;

        try {
            $items = DB::table('temp_detail_retur')
                ->where('id_users', Auth::user()->id)
                ->where('id_retur', $idReture)
                ->get();

            if ($items->isEmpty()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Data tidak ditemukan',
                    'status_code' => 404,
                ], 404);
            }

            $mappedData = $items->map(function ($item) {
                $kasir = Kasir::with(['toko', 'member'])->find($item->id_transaksi);
                $barang = Barang::find($item->id_barang);
                $retur = DataReture::find($item->id_retur);

                return [
                    'id' => $item->id,
                    'id_users' => $item->id_users,
                    'id_retur' => $item->id_retur,
                    'id_transaksi' => $item->id_transaksi,
                    'id_barang' => $item->id_barang,
                    'id_member' => $kasir->member->id ? $kasir->member->id : "Tidak Ditemukan",
                    'no_nota' => $item->no_nota,
                    'qty' => $item->qty,
                    'harga' => $item->harga,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'nama_toko' => $kasir->toko ? $kasir->toko->nama_toko : "Tidak Ditemukan",
                    'nama_member' => $kasir->member ? $kasir->member->nama_member : "Guest",
                    'nama_barang' => $barang ? $barang->nama_barang : "Tidak Ditemukan",
                    'tgl_retur' => $retur ? $retur->tgl_retur : "Tidak Ditemukan",
                ];
            });

            return response()->json([
                'error' => false,
                'message' => 'Successfully',
                'status_code' => 200,
                'data' => $mappedData,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching temporary items: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat mengambil data sementara.',
                'status_code' => 500,
            ], 500);
        }
    }

    public function getRetureItems(Request $request)
    {
        $request->validate([
            'id_retur' => 'required|integer',
        ]);

        $idReture = $request->id_retur;

        try {
            $items = DetailRetur::where('id_users', Auth::user()->id)
                                ->where('id_retur', $idReture)
                                ->get();

            if ($items->isEmpty()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Data tidak ditemukan',
                    'status_code' => 404,
                ], 404);
            }

            $mappedData = $items->map(function ($item) {
                $kasir = Kasir::with(['toko', 'member'])->find($item->id_transaksi);
                $barang = Barang::find($item->id_barang);
                $retur = DataReture::find($item->id_retur);
                $detailRetur = DetailRetur::where('id_transaksi', $item->id_transaksi)
                                            ->where('id_barang', $item->id_barang)
                                            ->where('id_retur', $item->id_retur)
                                            ->first();

                return [
                    'id' => $item->id,
                    'id_users' => $item->id_users,
                    'id_retur' => $item->id_retur,
                    'id_transaksi' => $item->id_transaksi,
                    'id_barang' => $item->id_barang,
                    'id_member' => $kasir->member->id ? $kasir->member->id : "Tidak Ditemukan",
                    'no_nota' => $item->no_nota,
                    'qty' => $item->qty,
                    'harga' => $item->harga,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'nama_toko' => $kasir->toko ? $kasir->toko->nama_toko : "Tidak Ditemukan",
                    'nama_member' => $kasir->member ? $kasir->member->nama_member : "Guest",
                    'nama_barang' => $barang ? $barang->nama_barang : "Tidak Ditemukan",
                    'tgl_retur' => $retur ? $retur->tgl_retur : "Tidak Ditemukan",
                    'status' => $detailRetur ? $detailRetur->status : "Tidak Ditemukan",
                ];
            });

            return response()->json([
                'error' => false,
                'message' => 'Successfully',
                'status_code' => 200,
                'data' => $mappedData,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching temporary items: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat mengambil data Reture.',
                'status_code' => 500,
            ], 500);
        }
    }

    public function getTempoData(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = DataReture::query();

        $query->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(no_nota) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(status) LIKE ?", ["%$searchTerm%"]);
            });
        }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $query->leftJoin('member', 'data_retur.id_member', '=', 'member.id')
          ->select('data_retur.*', 'member.nama_member');

        $data = $query->paginate($meta['limit']);

        $paginationMeta = [
            'total'        => $data->total(),
            'per_page'     => $data->perPage(),
            'current_page' => $data->currentPage(),
            'total_pages'  => $data->lastPage()
        ];

        $data = [
            'data' => $data->items(),
            'meta' => $paginationMeta
        ];

        if (empty($data['data'])) {
            return response()->json([
                'status_code' => 400,
                'errors' => true,
                'message' => 'Tidak ada data'
            ], 400);
        }

        $mappedData = collect($data['data'])->map(function ($item) {
            // Cek apakah ada data no_nota yang sama di tabel temp_detail_retur
            $existsInTemp = DB::table('temp_detail_retur')
                ->where('no_nota', $item['no_nota'])
                ->exists();

            // Cek apakah ada data no_nota yang sama di tabel detail_retur
            $existsInDetail = DetailRetur::where('no_nota', $item['no_nota'])
                ->exists();

            // Tentukan nilai action berdasarkan hasil pengecekan
            $action = 'none';
            if ($existsInTemp) {
                $action = 'edit_temp';
            } elseif ($existsInDetail) {
                $action = 'edit_detail';
            } else {
                $action = 'edit_temp';
            }

            return [
                'id' => $item['id'],
                'id_users' => $item['id_users'],
                'id_toko' => $item['id_toko'],
                'no_nota' => $item['no_nota'],
                'tgl_retur' => $item['tgl_retur'],
                'status' => $item['status'],
                'id_member' => $item['id_member'],
                'nama_member' => $item['nama_member'],
                'created_at' => $item['created_at'],
                'updated_at' => $item['updated_at'],
                'action' => $action,
            ];
        });

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Sukses',
            'pagination' => $data['meta']
        ], 200);
    }

    public function saveTemporaryItems(Request $request)
    {
        $request->validate([
            'id_retur' => 'required|integer',
            'no_nota' => 'required|string',
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

    public function deleteRowTable(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|integer',
            'id_transaksi' => 'required|integer',
        ]);

        $userId = Auth::user()->id;

        try {

            DB::table('temp_detail_retur')
                ->where('id_users', $userId)
                ->where('id_barang', $request->id_barang)
                ->where('id_transaksi', $request->id_transaksi)
                ->delete();

            return response()->json([
                'error' => false,
                'message' => 'Data berhasil dihapus!',
                'status_code' => 200,
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting row: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'status_code' => 500,
            ], 500);
        }
    }

    public function updateNotaReture(Request $request)
    {
        $request->validate([
            'metode' => 'required|array',
            'id_transaksi' => 'required|array',
            'id_barang' => 'required|array',
            'qty' => 'required|array',
            'harga' => 'required|array',
            'id_retur' => 'required|integer',
        ]);

        $metode = $request->metode;
        $qrcode = $request->qrcode;
        $id_kasir = $request->id_transaksi;
        $id_barang = $request->id_barang;
        $qty = $request->qty;
        $harga = $request->harga;
        $id_retur = $request->id_retur;

        $id_users = Auth::user()->id;

        try {
            DB::beginTransaction();

            foreach ($id_kasir as $index => $idKasir) {
                if ($metode[$index] === 'Cash') {
                    $detailKasir = DetailKasir::where('id_kasir', $idKasir)
                        ->where('id_barang', $id_barang[$index])
                        ->first();

                    if ($detailKasir) {

                        $detailKasir->reture = true;
                        $detailKasir->reture_by = $id_users;

                        if (is_null($detailKasir->reture_qty)) {
                            $detailKasir->reture_qty = 0;
                        }

                        $detailKasir->reture_qty += $qty[$index];

                        $detailKasir->save();
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => 'Data tidak ditemukan untuk id_transaksi: ' . $idKasir . ' dan id_barang: ' . $id_barang[$index],
                            'status_code' => 404,
                        ], 404);
                    }
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Metode tidak valid untuk id_transaksi: ' . $idKasir . ' dan id_barang: ' . $id_barang[$index],
                        'status_code' => 400,
                    ], 400);
                }

                // Update status di tabel detail_retur
                DetailRetur::where('id_transaksi', $idKasir)
                    ->where('id_barang', $id_barang[$index])
                    ->where('id_retur', $id_retur)
                    ->update(['status' => 'success']);
            }

            // Update total_item dan total_harga di tabel retur
            $totalItem = DetailRetur::where('id_retur', $id_retur)
                                    ->sum('qty');

            $totalHarga = DetailRetur::where('id_retur', $id_retur)
                                    ->sum(DB::raw('qty * harga'));

            DataReture::where('id', $id_retur)
                        ->update([
                            'total_item' => $totalItem,
                            'total_harga' => $totalHarga,
                            'status' => 'done'
                        ]);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Data berhasil diupdate!',
                'status_code' => 200,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating nota reture: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat mengupdate data.',
                'status_code' => 500,
            ], 500);
        }
    }

}
