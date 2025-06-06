<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\PembelianBarangImport;
use App\Models\Barang;
use App\Models\DetailPembelianBarang;
use App\Models\DetailStockBarang;
use App\Models\LevelHarga;
use App\Models\PembelianBarang;
use App\Models\StockBarang;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\NotoSans;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PembelianBarangController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Data Pembelian Barang',
            'Detail Data'
        ];
    }

    public function getpembelianbarang(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = PembelianBarang::query();

        $query->with(['barang', 'supplier', 'level_harga'])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(no_nota) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereHas('supplier', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_supplier) LIKE ?", ["%$searchTerm%"]);
                });
            });
        }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            $query->whereBetween('tgl_nota', [$startDate, $endDate]);
        }

        $totalNilai = $query->sum('total_nilai');
        $totalItem = $query->sum('total_item');

        $data = $query->paginate($meta['limit']);

        $paginationMeta = [
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'total_pages' => $data->lastPage()
        ];

        $mappedData = collect($data->items())->map(function ($item) {
            // Jika status "progress", ambil total_item dan total_nilai dari temp_detail_pembelian_barang
            if ($item->status === 'progress') {
                $tempData = DB::table('temp_detail_pembelian_barang')
                    ->where('id_pembelian_barang', $item->id)
                    ->selectRaw('SUM(qty) as total_item, SUM(harga_barang * qty) as total_nilai')
                    ->first();

                $totalItem = $tempData->total_item ?? 0;
                $totalNilai = $tempData->total_nilai ?? 0;
            } else {
                $totalItem = $item->total_item;
                $totalNilai = $item->total_nilai;
            }

            return [
                'id' => $item->id,
                'nama_supplier' => $item->supplier->nama_supplier,
                'status' => match ($item->status) {
                    'success' => 'Sukses',
                    'failed' => 'Gagal',
                    default => $item->status,
                },
                'tgl_nota' => Carbon::parse($item->tgl_nota)->format('d-m-Y'),
                'no_nota' => $item->no_nota,
                'total_item' => $totalItem,
                'total_nilai' => 'Rp. ' . number_format($totalNilai, 0, ',', '.'),
            ];
        });

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Sukses',
            'pagination' => $paginationMeta,
            'total' => 'Rp. ' . number_format($totalNilai, 0, '.', '.'),
            'totals' => $totalItem
        ], 200);
    }


    public function index(Request $request)
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }
        $menu = [$this->title[0], $this->label[1]];
        $suppliers = Supplier::all();       // Kirim data ke view
        $barang = Barang::all();       // Kirim data ke view
        $LevelHarga = LevelHarga::all();       // Kirim data ke view
        return view('transaksi.pembelianbarang.index', compact('menu', 'suppliers', 'barang', 'LevelHarga'));
    }

    public function create()
    {
        $menu = [$this->title[0], $this->label[1], $this->title[1]];
        $barang = Barang::all();
        $suppliers = Supplier::all();
        $LevelHarga = LevelHarga::all();

        return view('transaksi.pembelianbarang.create', compact('menu', 'suppliers', 'barang', 'LevelHarga'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id',
            'tgl_nota' => 'required|date',
            'no_nota' => 'required|numeric|unique:pembelian_barang,no_nota',
        ], [
            'no_nota.unique' => 'Nomor Nota sudah digunakan !',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Periksa apakah tgl_nota hanya berisi tanggal tanpa waktu
            $tglNota = Carbon::parse($request->tgl_nota);
            if ($tglNota->format('H:i:s') === '00:00:00') {
                // Tambahkan waktu default (waktu saat ini)
                $tglNota->setTimeFromTimeString(Carbon::now()->format('H:i:s'));
            }

            $pembelian = PembelianBarang::create([
                'id_supplier' => $request->id_supplier,
                'id_users' => $user->id,
                'no_nota' => $request->no_nota,
                'tgl_nota' => $tglNota,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'no_nota' => $pembelian->no_nota,
                'nama_supplier' => $pembelian->supplier->nama_supplier,
                'tgl_nota' => $pembelian->tgl_nota,
                'id_pembelian' => $pembelian->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Kembalikan response error dalam format JSON
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function detail($id)
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[1], $this->title[1]];

        return view('transaksi.pembelianbarang.edit', compact('menu'));
    }

    public function getDetailPembelian(Request $request)
    {
        $id = $request->input('id_pembelian');

        if (!$id) {
            return response()->json([
                'status' => 'error',
                'errors' => true,
                'status_code' => 400,
                'message' => 'ID Pembelian tidak ditemukan',
            ], 400);
        }

        $pembelian = PembelianBarang::with(['supplier', 'detail.barang'])->find($id);

        if (!$pembelian) {
            return response()->json([
                'status' => 'error',
                'errors' => true,
                'status_code' => 404,
                'message' => 'Pembelian tidak ditemukan',
            ], 404);
        }

        $detail = $pembelian->detail->map(function ($item) {
            return [
                'status' => $item->status,
                'qrcode' => $item->qrcode,
                'qrcode_path' => $item->qrcode_path,
                'nama_barang' => $item->barang->nama_barang ?? '-',
                'qty' => $item->qty,
                'harga_barang' => $item->harga_barang,
                'total_harga' => $item->total_harga,
            ];
        });

        $subTotal = $detail->sum(function ($item) {
            return $item['qty'] * $item['harga_barang'];
        });

        return response()->json([
            'data' => [
                'no_nota' => $pembelian->no_nota,
                'nama_supplier' => $pembelian->supplier->nama_supplier ?? '-',
                'tgl_nota' => $pembelian->tgl_nota,
                'sub_total' => $subTotal,
                'detail' => $detail,
            ],
            'status_code' => 200,
            'errors' => false,
            'message' => 'Sukses',
        ]);
    }

    public function getStock($id_barang)
    {
        $stock = StockBarang::where('id_barang', $id_barang)->first();

        $barang = Barang::where('id', $id_barang)->first();

        $detail = DetailPembelianBarang::where('id_barang', $id_barang)->get();

        $totalHargaSuccess = $detail->sum('total_harga');
        $totalQtySuccess = $detail->sum('qty');

        // Hitung HPP baru
        if ($totalQtySuccess > 0) {
            $hppBaru = $totalHargaSuccess / $totalQtySuccess;
        } else {
            $hppBaru = 0;
        }

        $level_harga = [];
        if ($barang && $barang->level_harga) {
            $decoded_level_harga = json_decode($stock->level_harga, true);
            foreach ($decoded_level_harga as $item) {
                list($level_name, $level_value) = explode(' : ', $item);
                $level_harga[$level_name] = $level_value;
            }
        }

        return response()->json([
            'stock' => $stock->stock ?? 0,
            'hpp_awal' => $stock->hpp_awal ?? 0,
            'hpp_baru' => $hppBaru,
            'level_harga' => $level_harga,
        ]);
    }

    public function update(Request $request, $id)
    {
        $idBarangs = $request->input('id_barang', []);
        $qtys = $request->input('qty', []);
        $hargaBarangs = $request->input('harga_barang', []);
        $levelNamas = $request->input('level_nama', []);
        $levelHargas = $request->input('level_harga', []);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Ambil pembelian
            $pembelian = PembelianBarang::findOrFail($id);

            $totalItem = 0;
            $totalNilai = 0;

            $counter = 1;

            // Ambil data dari temp_detail_pembelian_barang berdasarkan id_pembelian_barang
            $tempDetails = DB::table('temp_detail_pembelian_barang')
                ->where('id_pembelian_barang', $id)
                ->get();

            foreach ($tempDetails as $tempDetail) {
                $id_barang = $tempDetail->id_barang;
                $qty = $tempDetail->qty;
                $harga_barang = $tempDetail->harga_barang;

                if ($id_barang && $qty > 0 && $harga_barang > 0) {
                    $barang = Barang::findOrFail($id_barang);

                    // Generate QR Code Value
                    $tglNota = Carbon::parse($pembelian->tgl_nota)->format('dmY');
                    $idSupplier = $pembelian->id_supplier;
                    $idPembelian = $pembelian->id;
                    $qrCodeValue = "{$tglNota}SP{$idSupplier}ID{$idPembelian}-{$counter}";

                    // Path QR code for this barang
                    $qrCodePath = "qrcodes/pembelian/{$idPembelian}-{$counter}.png";
                    $fullPath = public_path($qrCodePath);

                    if (!file_exists(dirname($fullPath))) {
                        mkdir(dirname($fullPath), 0755, true);
                    }

                    // Generate QR Code
                    $qrCode = QrCode::create($qrCodeValue)
                        ->setEncoding(new Encoding('UTF-8'))
                        ->setSize(200)
                        ->setMargin(10);

                    $writer = new PngWriter();
                    $result = $writer->write($qrCode);
                    // $result = $writer->write(
                    //     $qrCode,
                    //     null,
                    //     Label::create("{$barang->nama_barang}")
                    //         ->setFont(new NotoSans(12))
                    // );

                    $result->saveToFile($fullPath);

                    // Insert atau Update ke Detail Pembelian Barang
                    $detail = DetailPembelianBarang::updateOrCreate(
                        [
                            'id_pembelian_barang' => $pembelian->id,
                            'id_barang' => $id_barang,
                            'id_supplier' => $idSupplier,
                        ],
                        [
                            'qty' => $qty,
                            'harga_barang' => $harga_barang,
                            'total_harga' => $qty * $harga_barang,
                            'qrcode' => $qrCodeValue,
                            'qrcode_path' => $qrCodePath,
                        ]
                    );

                    $detail->status = 'success';
                    $detail->save();

                    // Update total items and total nilai
                    $totalItem += $detail->qty;
                    $totalNilai += $detail->total_harga;

                    // Process Level Harga
                    $levelHargaBarang = [];

                    // Ambil level harga dari request jika ada, jika tidak gunakan dari tempDetail
                    if (!empty($levelHargas[$id_barang]) && is_array($levelHargas[$id_barang])) {
                        foreach ($levelHargas[$id_barang] as $levelIndex => $hargaLevel) {
                            $levelNama = $levelNamas[$levelIndex] ?? 'Level ' . ($levelIndex + 1);
                            if (!is_null($hargaLevel)) {
                                $levelHargaBarang[] = "{$levelNama} : {$hargaLevel}";
                            }
                        }
                    } else {
                        // Ambil dari temp jika tidak ada di request
                        $levelHargaBarang = json_decode($tempDetail->level_harga, true) ?? [];
                    }

                    // Simpan Level Harga ke tabel Barang
                    $barang->level_harga = json_encode($levelHargaBarang);
                    $barang->save();

                    // Update stockBarang
                    $stockBarang = StockBarang::firstOrNew(['id_barang' => $id_barang]);

                    $hpp_awal = $stockBarang->hpp_baru ?: $stockBarang->hpp_awal ?: $harga_barang;
                    $stock_awal = $stockBarang->stock ?: 0;

                    // Cari qty dari tabel detail_toko
                    $qty_detail_toko = DB::table('detail_toko')
                        ->where('id_barang', $id_barang)
                        ->sum('qty');

                    // Hitung total stok lama
                    $total_stock_lama = $stock_awal + $qty_detail_toko;

                    // Hitung nilai total lama
                    $nilai_total_lama = $total_stock_lama * $hpp_awal;

                    // Hitung nilai pembelian baru
                    $nilai_pembelian_baru = $qty * $harga_barang;

                    // Hitung total qty setelah pembelian
                    $total_qty_baru = $total_stock_lama + $qty;

                    // Hitung HPP baru
                    $hpp_baru = $total_qty_baru > 0 ? ($nilai_total_lama + $nilai_pembelian_baru) / $total_qty_baru : $hpp_awal;

                    $stockBarang->stock = $stock_awal + $detail->qty;
                    $stockBarang->hpp_awal = $hpp_awal;
                    $stockBarang->hpp_baru = ($hpp_baru);
                    $stockBarang->nilai_total = ($hpp_baru * $stockBarang->stock);
                    $stockBarang->nama_barang = $barang->nama_barang;
                    $stockBarang->save();

                    DetailStockBarang::create([
                        'id_stock' => $stockBarang->id,
                        'id_barang' => $id_barang,
                        'id_supplier' => $idSupplier,
                        'id_pembelian' => $pembelian->id,
                        'id_detail_pembelian' => $detail->id,
                        'qty_buy' => $qty,
                        'qty_now' => $qty,
                    ]);

                    $counter++;
                }
            }

            // Update pembelian with total item and total nilai
            $pembelian->total_item = $totalItem;
            $pembelian->total_nilai = $totalNilai;
            $pembelian->id_users = $user->id;
            $pembelian->status = 'success';
            $pembelian->save();

            // Remove data from temp_detail_pembelian_barang
            DB::table('temp_detail_pembelian_barang')
                ->where('id_pembelian_barang', $pembelian->id)
                ->delete();

            DB::commit();

            return redirect()->route('transaksi.pembelianbarang.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Failed to update pembelian barang. ' . $e->getMessage()]);
        }
    }

    public function gettemppembelian(Request $request)
    {
        try {
            // Ambil id_pembelian dari request
            $id_pembelian = $request->input('id_pembelian');

            // Ambil data dari tabel berdasarkan id_pembelian dan join ke tabel barang
            $tempDetails = DB::table('temp_detail_pembelian_barang')
                ->join('barang', 'temp_detail_pembelian_barang.id_barang', '=', 'barang.id') // Join dengan tabel barang
                ->select(
                    'temp_detail_pembelian_barang.id_pembelian_barang',
                    'temp_detail_pembelian_barang.id_barang',
                    'barang.nama_barang', // Ambil nama_barang dari tabel barang
                    'temp_detail_pembelian_barang.qty',
                    'temp_detail_pembelian_barang.harga_barang',
                    'temp_detail_pembelian_barang.total_harga',
                    'temp_detail_pembelian_barang.level_harga'
                )
                ->where('temp_detail_pembelian_barang.id_pembelian_barang', $id_pembelian)
                ->get();


            // Decode kolom level_harga dari JSON ke array
            foreach ($tempDetails as $detail) {
                $detail->level_harga = json_decode($detail->level_harga);
            }

            // Kirimkan response JSON
            return response()->json([
                'status' => 'success',
                'errors' => false,
                'status_code' => 200,
                'message' => 'Data berhasil diambil',
                'data' => $tempDetails,
            ]);
        } catch (\Exception $e) {
            // Tangani error dan kirimkan response JSON
            return response()->json([
                'status' => 'error',
                'errors' => true,
                'status_code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Konz
    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $pembelian = PembelianBarang::findOrFail($id);

            $pembelian->detail()->delete();

            $pembelian->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Success to delete pembelian barang. ']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['success' => false, 'message' => 'Failed to delete pembelian barang. ' . $e->getMessage()]);
        }
    }

    public function storeTemp(Request $request)
    {
        try {
            $request->validate([
                'id_pembelian' => 'required|exists:pembelian_barang,id',
                'id_barang' => 'required|exists:barang,id',
                'qty' => 'required|numeric|min:1',
                'harga_barang' => 'required|numeric|min:1',
                'level_harga' => 'array',
                'level_harga.*' => 'string',
            ]);

            $tempDetail = DB::table('temp_detail_pembelian_barang')->insert([
                'id_pembelian_barang' => $request->id_pembelian,
                'id_barang' => $request->id_barang,
                'qty' => $request->qty,
                'harga_barang' => $request->harga_barang,
                'total_harga' => $request->qty * $request->harga_barang,
                'level_harga' => json_encode($request->level_harga),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => $tempDetail
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function hapusTemp(Request $request)
    {
        try {
            $request->validate([
                'id_pembelian' => 'required|exists:temp_detail_pembelian_barang,id_pembelian_barang',
                'id_barang' => 'required|exists:temp_detail_pembelian_barang,id_barang'
            ]);

            $deleted = DB::table('temp_detail_pembelian_barang')
                ->where('id_pembelian_barang', $request->id_pembelian)
                ->where('id_barang', $request->id_barang)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data Berhasil diEdit'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan atau sudah diHapus'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new PembelianBarangImport, $request->file('file'));

        return back()->with('success', 'Data berhasil diimpor!');
    }
}
