<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DataReture;
use App\Models\DetailKasir;
use App\Models\DetailPembelianBarang;
use App\Models\DetailRetur;
use App\Models\DetailStockBarang;
use App\Models\DetailToko;
use App\Models\Kasir;
use App\Models\Member;
use App\Models\StockBarang;
use App\Models\Supplier;
use Carbon\Carbon;
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
            'Retur dari Member',
        ];
    }

    public function index()
    {
        $menu = [$this->title[0], $this->label[3]];
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

                    if ($barang->garansi == "No") {
                        return response()->json([
                            "error" => true,
                            "message" => "Barang tidak bisa di Reture karena tidak ada garansi",
                            "status_code" => 400,
                        ], 400);
                    }

                    $diskon = $detailKasir->diskon ?? 0;
                    $reture_qty = $detailKasir->reture_qty ?? 0;

                    $detail_beli = DetailPembelianBarang::find($detailKasir->id_detail_pembelian);

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
                            "qrcode" => $detail_beli->qrcode,
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
            'tgl_retur' => 'required|date',
            'id_member' => 'required|string',
        ]);

        $user = Auth::user();

        $tglRetur = Carbon::parse($request->tgl_retur);
        if ($tglRetur->format('H:i:s') === '00:00:00') {
            $tglRetur->setTimeFromTimeString(Carbon::now()->format('H:i:s'));
        }

        $tglFormatted = $tglRetur->format('ymd'); // yyMMdd
        $idMember = $request->id_member;

        // Buat no_nota yang unik dengan 3 digit random
        do {
            $randomNumber = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
            $noNota = "R{$tglFormatted}{$idMember}-{$randomNumber}";
            $existing = DataReture::where('no_nota', $noNota)->exists();
        } while ($existing);

        try {
            $retur = DataReture::create([
                'id_users' => $user->id,
                'id_toko' => $user->id_toko,
                'no_nota' => $noNota,
                'tgl_retur' => $tglRetur,
                'id_member' => $idMember,
                'tipe_transaksi' => 'kasir',
            ]);

            $member = Member::find($idMember);

            return response()->json([
                'error' => false,
                'message' => 'Successfully',
                'status_code' => 200,
                'data' => [
                    'id_retur' => $retur->id,
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
            'qrcode' => 'required|string'
        ]);

        $user = Auth::user();

        try {
            DB::beginTransaction();

            // Get hpp_baru from stock_barang
            $stockBarang = StockBarang::where('id_barang', $request->input('id_barang'))->first();
            if (!$stockBarang) {
                return response()->json([
                    'error' => true,
                    'message' => 'Data stock barang tidak ditemukan.',
                    'status_code' => 404,
                ], 404);
            }

            DB::table('temp_detail_retur')->insert([
                'id_users' => $user->id,
                'id_retur' => $request->input('id_retur'),
                'qrcode' => $request->input('qrcode'),
                'id_transaksi' => $request->input('id_transaksi'),
                'id_barang' => $request->input('id_barang'),
                'no_nota' => $request->input('no_nota'),
                'qty' => $request->input('qty'),
                'harga' => $request->input('harga'),
                'hpp_baru' => $stockBarang->hpp,
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
                    'qrcode' => $item->qrcode,
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
        $user = Auth::user();

        try {
            // Jika user adalah admin (id_level = 1), tampilkan semua data sesuai id_retur
            if ($user->id_level == 1) {
                $items = DetailRetur::where('id_retur', $idReture)->get();
            } else {
                // Jika bukan admin, filter berdasarkan id_users
                $items = DetailRetur::where('id_users', $user->id)
                    ->where('id_retur', $idReture)
                    ->get();
            }

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
                    'id_member' => $kasir->member->id ?? "Tidak Ditemukan",
                    'no_nota' => $item->no_nota,
                    'qty' => $item->qty,
                    'qrcode' => $item->qrcode,
                    'harga' => $item->harga,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'nama_toko' => $kasir->toko->nama_toko ?? "Tidak Ditemukan",
                    'nama_member' => $kasir->member->nama_member ?? "Guest",
                    'nama_barang' => $barang->nama_barang ?? "Tidak Ditemukan",
                    'tgl_retur' => $retur->tgl_retur ?? "Tidak Ditemukan",
                    'status' => $detailRetur->status ?? "Tidak Ditemukan",
                    'metode' => $detailRetur->metode ?? "Tidak Ditemukan",
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

        $query = DataReture::where('tipe_transaksi', 'kasir');

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
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'total_pages' => $data->lastPage()
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
            'qrcode' => 'required|array'
        ]);

        $userId = Auth::user()->id;
        $idRetur = $request->id_retur;
        $noNota = $request->no_nota;
        $idTransaksi = $request->id_transaksi;
        $idBarang = $request->id_barang;
        $qty = $request->qty;
        $harga = $request->harga;
        $qrcode = $request->qrcode;

        try {
            DB::beginTransaction();

            foreach ($idTransaksi as $index => $idTrans) {
                DB::table('detail_retur')->insert([
                    'id_users' => $userId,
                    'id_retur' => $idRetur,
                    'id_transaksi' => $idTrans,
                    'id_barang' => $idBarang[$index],
                    'qrcode' => $qrcode[$index],
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
            'hpp_baru' => 'required|array',
            'stock_qty' => 'required|array',
            'qrcode_toko' => 'required|array',
            'qrcode_barang' => 'required|array'
        ]);

        $metode = $request->metode;
        $id_kasir = $request->id_transaksi;
        $id_barang = $request->id_barang;
        $qty = array_map('intval', $request->qty);
        $id_retur = $request->id_retur;
        $hpp = $request->hpp_baru;
        $stock = $request->stock_qty;
        $qrcode_toko = $request->qrcode_toko;
        $qrcode_barang = $request->qrcode_barang;
        $id_users = Auth::user()->id;
        $id_toko = Auth::user()->id_toko;

        try {
            DB::beginTransaction();

            foreach ($id_kasir as $index => $idKasir) {
                if ($metode[$index] === 'Cash') {
                    $detailPembelian = DetailPembelianBarang::where('qrcode', $qrcode_barang[$index])->first();

                    $detailKasir = DetailKasir::where('id_kasir', $idKasir)
                        ->where('id_barang', $id_barang[$index])
                        ->where('id_detail_pembelian', $detailPembelian->id)
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

                    // Update status di tabel detail_retur
                    DetailRetur::where('id_transaksi', $idKasir)
                        ->where('id_barang', $id_barang[$index])
                        ->where('id_retur', $id_retur)
                        ->update([
                            'status' => 'success',
                            'qty_acc' => $qty[$index],
                            'hpp_jual' => $detailKasir->hpp_jual,
                            'metode' => $metode[$index],
                        ]);
                } elseif ($metode[$index] === 'Barang') {
                    $detailPembelian = DetailPembelianBarang::where('qrcode', $qrcode_toko[$index])->first();

                    $detailKasir = DetailKasir::where('id_kasir', $idKasir)
                        ->where('id_barang', $id_barang[$index])
                        ->first();

                    if ($detailKasir) {
                        $detailKasir->reture = true;
                        $detailKasir->reture_by = $id_users;

                        if (is_null($detailKasir->reture_qty)) {
                            $detailKasir->reture_qty = 0;
                        }

                        $reture_qty = $qty[$index] - $stock[$index];

                        if ($reture_qty == 0) {
                            $reture_qty = $stock[$index];
                        }

                        $detailKasir->reture_qty += $reture_qty;

                        $detailKasir->save();

                        // Update stok berdasarkan id_toko
                        if ($id_toko == 1) {
                            // Kurangi stok di tabel StockBarang
                            $detailStock = DetailStockBarang::where('id_detail_pembelian', $detailPembelian->id)
                                ->first();

                            $stockBarang = StockBarang::where('id_barang', $id_barang[$index])
                                ->first();

                            if ($stockBarang && $detailStock) {
                                $stockBarang->stock -= $reture_qty;
                                $stockBarang->save();

                                $detailStock->qty_now -= $reture_qty;
                                $detailStock->save();
                            } else {
                                return response()->json([
                                    'error' => true,
                                    'message' => 'Stok tidak ditemukan untuk id_barang: ' . $id_barang[$index],
                                    'status_code' => 404,
                                ], 404);
                            }
                        } else {
                            // Kurangi qty di tabel DetailToko
                            $detailToko = DetailToko::where('id_toko', $id_toko)
                                ->where('id_barang', $id_barang[$index])
                                ->where('qrcode', $qrcode_toko[$index])
                                ->first();

                            if ($detailToko) {
                                $detailToko->qty -= $reture_qty;
                                $detailToko->save();
                            } else {
                                return response()->json([
                                    'error' => true,
                                    'message' => 'Stok tidak ditemukan untuk id_barang: ' . $id_barang[$index] . ' di toko: ' . $id_toko,
                                    'status_code' => 404,
                                ], 404);
                            }
                        }
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => 'Data Kasir kosong',
                            'status_code' => 404,
                        ], 404);
                    }

                    // Update status di tabel detail_retur
                    DetailRetur::where('id_transaksi', $idKasir)
                        ->where('id_barang', $id_barang[$index])
                        ->where('id_retur', $id_retur)
                        ->update([
                            'status' => 'success',
                            'hpp_jual' => $hpp[$index],
                            'qrcode_barang' => $qrcode_toko[$index],
                            'qty_acc' => $stock[$index],
                            'metode' => $metode[$index],
                        ]);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Metode tidak valid untuk id_transaksi: ' . $idKasir . ' dan id_barang: ' . $id_barang[$index],
                        'status_code' => 400,
                    ], 400);
                }
            }

            // Update total_item dan total_harga di tabel retur
            $totalItem = DetailRetur::where('id_retur', $id_retur)
                ->sum('qty_acc');

            $totalHarga = DetailRetur::where('id_retur', $id_retur)
                ->sum(DB::raw('qty_acc * harga'));

            DataReture::where('id', $id_retur)
                ->update([
                    'total_item' => $totalItem,
                    'total_harga' => $totalHarga,
                    'status' => 'done',
                    'tipe_transaksi' => 'kasir'
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
                'message' => 'Terjadi kesalahan saat mengupdate data.' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }

    public function getRetureQrcode(Request $request)
    {
        try {
            $qrcode = $request->input('qrcode');
            $qrcode_barang = $request->input('qrcode_barang');
            $id_toko = $request->input('id_toko');
            $id_barang = $request->input('id_barang');
            $id_transaksi = $request->input('id_transaksi');

            // Cek apakah barcode ada di tabel barang
            $barang = DetailPembelianBarang::where('qrcode', $qrcode)
                ->where('id_barang', $id_barang)
                ->first();

            if (!$barang) {
                return response()->json(['message' => 'Tidak ada qrcode atau barang yang ditemukan'], 404);
            }

            // Cek apakah id_barang sesuai dengan barcode
            if ($barang->id_barang != $id_barang) {
                return response()->json(['message' => 'Qrcode tidak sesuai dengan barang'], 400);
            }

            $detailPembelian = DetailPembelianBarang::where('qrcode', $qrcode_barang)->first();

            if (!$detailPembelian) {
                return response()->json(['message' => 'Qrcode barang tidak ditemukan'], 404);
            }

            $detailKasir = DetailKasir::where('id_kasir', $id_transaksi)
                ->where('id_barang', $id_barang)
                ->where('id_detail_pembelian', $detailPembelian->id)
                ->first();

            if ($id_toko == 1) {
                // Cek stok barang di tabel StockBarang
                $stock = DetailStockBarang::where('id_barang', $id_barang)
                    ->where('id_detail_pembelian', $barang->id)
                    ->first();

                if (!$stock) {
                    return response()->json(['message' => 'Stok barang tidak ditemukan'], 404);
                }

                $response_data = [
                    'id_barang' => $stock->id_barang,
                    'nama_barang' => $barang->barang->nama_barang,
                    'stock_toko_qty' => $stock->qty_now,
                    'hpp_baru' => $detailKasir->hpp_jual,
                ];
            } elseif ($id_toko != 1) {
                $stock_toko = DetailToko::where('id_toko', $id_toko)
                    ->where('id_barang', $id_barang)
                    ->where('qrcode', $qrcode)
                    ->first();

                if (!$stock_toko) {
                    return response()->json(['message' => 'Stok barang tidak ditemukan'], 404);
                }

                if ($stock_toko->qty == 0) {
                    return response()->json(['message' => 'Barang sedang kosong'], 404);
                }

                $response_data = [
                    'id_barang' => $stock_toko->id_barang,
                    'nama_barang' => $barang->nama_barang,
                    'stock_toko_qty' => $stock_toko->qty,
                    'hpp_baru' => $detailKasir->hpp_jual,
                ];
            } else {
                return response()->json(['message' => 'Toko tidak ditemukan'], 404);
            }

            return response()->json([
                'error' => false,
                'message' => 'Data ditemukan!',
                'status_code' => 200,
                'data' => $response_data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan pada server' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }

    public function storeNotaSupplier(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required|string',
            'tgl_retur' => 'required|date',
            'no_nota' => 'required|string',
        ]);

        $user = Auth::user();

        $tglRetur = Carbon::parse($request->tgl_retur);
        if ($tglRetur->format('H:i:s') === '00:00:00') {
            // Tambahkan waktu default (waktu saat ini)
            $tglRetur->setTimeFromTimeString(Carbon::now()->format('H:i:s'));
        }

        try {
            $supplier = Supplier::where('id', $request->id_supplier)->first();

            $detailKasir = DetailKasir::where('id_supplier', $request->id_supplier)->get();

            if ($detailKasir->isEmpty()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Data tidak ditemukan',
                    'status_code' => 404,
                ], 404);
            } else {
                $detailTransaksi = DetailRetur::join('detail_pembelian_barang', 'detail_retur.qrcode', '=', 'detail_pembelian_barang.qrcode')
                    ->whereIn('detail_retur.id_transaksi', $detailKasir->pluck('id_kasir'))
                    ->where('detail_retur.status', 'success')
                    ->where('detail_retur.status_reture', 'pending')
                    ->where('detail_retur.status_kirim', 'success')
                    ->select('detail_retur.*', 'detail_pembelian_barang.harga_barang as hpp_jual', 'detail_pembelian_barang.qrcode')
                    ->get();

                if ($detailTransaksi->isEmpty()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Tidak ada Barang yang bisa di Reture',
                        'status_code' => 404,
                    ], 404);
                }
            }

            $retur = DataReture::create([
                'id_users' => $user->id,
                'id_toko' => $user->id_toko,
                'no_nota' => $request->no_nota,
                'tgl_retur' => $tglRetur,
                'id_supplier' => $request->id_supplier,
                'tipe_transaksi' => 'supplier',
            ]);

            // Ambil data barang berdasarkan id_barang dari detailTransaksi
            $barang = Barang::whereIn('id', $detailTransaksi->pluck('id_barang'))->get();

            // Map nama_barang dari koleksi Barang
            $namaBarang = $barang->mapWithKeys(function ($item) {
                return [$item->id => $item->nama_barang];
            });

            // Map detailTransaksi untuk menambahkan nama_barang
            $detailTransaksi = $detailTransaksi->map(function ($item) use ($namaBarang) {
                return [
                    'id_transaksi' => $item->id_transaksi,
                    'id_retur' => $item->id_retur,
                    'id_barang' => $item->id_barang,
                    'nama_barang' => $namaBarang[$item->id_barang] ?? null,
                    'no_nota' => $item->no_nota,
                    'qty_acc' => $item->qty_acc,
                    'metode' => $item->metode,
                    'hpp_jual' => $item->hpp_jual,
                    'qrcode' => $item->qrcode,
                ];
            });

            // Return JSON response
            return response()->json([
                'error' => false,
                'message' => 'Successfully',
                'status_code' => 200,
                'data' => [
                    'id_retur' => $retur->id,
                    'no_nota' => $retur->no_nota,
                    'tgl_retur' => $retur->tgl_retur,
                    'nama_supplier' => $supplier->nama_supplier,
                ],
                'detail_retur' => $detailTransaksi,
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                "error" => true,
                "message" => "Terjadi kesalahan pada server: " . $th->getMessage(),
                "status_code" => 500,
            ], 500);
        }
    }

    public function deleteTempItem(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $id = $request->id;

        try {
            // Check if data exists in temp_detail_retur
            $tempExists = DB::table('temp_detail_retur')
                ->where('id_retur', $id)
                ->exists();

            if ($tempExists) {
                // If exists, delete from temp_detail_retur
                DB::table('temp_detail_retur')
                    ->where('id_retur', $id)
                    ->delete();
            } else {
                // If not exists, delete from detail_retur
                DetailRetur::where('id_retur', $id)
                    ->delete();
            }

            // Delete from data_reture
            DataReture::where('id', $id)
                ->delete();

            return response()->json([
                'error' => false,
                'message' => 'Data berhasil dihapus!',
                'status_code' => 200,
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting temporary item: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menghapus data sementara.' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }
}
