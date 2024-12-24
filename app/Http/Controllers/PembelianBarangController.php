<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPembelianBarang;
use App\Models\LevelHarga;
use App\Models\PembelianBarang;
use App\Models\StockBarang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\NotoSans;

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

        $query->with(['barang', 'supplier', 'level_harga'])->orderBy('tgl_nota', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                // Pencarian pada kolom langsung
                $query->orWhereRaw("LOWER(no_nota) LIKE ?", ["%$searchTerm%"]);

                // Pencarian pada relasi 'supplier->nama_supplier'
                $query->orWhereHas('supplier', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_supplier) LIKE ?", ["%$searchTerm%"]);
                });
            });
        }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            // Lakukan filter berdasarkan tanggal
            $query->whereBetween('tgl_nota', [$startDate, $endDate]);
        }

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
            return [
                'id' => $item['id'],
                'nama_supplier' => $item['supplier']->nama_supplier,
                'status' => match ($item->status) {
                    'success' => 'Sukses',
                    'failed' => 'Gagal',
                    default => $item->status,
                },
                'tgl_nota' => \Carbon\Carbon::parse($item->tgl_nota)->format('d-m-Y'),
                'no_nota' => $item->no_nota,
                'total_item' => $item->total_item,
                'total_nilai' => 'Rp. ' . number_format($item->total_nilai, 0, ',', '.'),
            ];
        });

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => true,
            'message' => 'Sukses',
            'pagination' => $data['meta']
        ], 200);
    }

    public function index(Request $request)
    {
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
            'no_nota' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $pembelian = PembelianBarang::create([
                'id_supplier' => $request->id_supplier,
                'no_nota' => $request->no_nota,
                'tgl_nota' => $request->tgl_nota,
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

    public function edit($id)
    {
        $menu = [$this->title[0], $this->label[1], $this->title[1]];
        $pembelian = PembelianBarang::with('detail')->findOrFail($id);
        $LevelHarga = LevelHarga::all();

        return view('transaksi.pembelianbarang.edit', compact('menu', 'pembelian', 'LevelHarga'));
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

    // public function update(Request $request, $id)
    // {
    //     $idBarangs = $request->input('id_barang', []);
    //     $qtys = $request->input('qty', []);
    //     $hargaBarangs = $request->input('harga_barang', []);
    //     $levelNamas = $request->input('level_nama', []);
    //     $levelHargas = $request->input('level_harga', []);

    //     try {
    //         // dd($request->all());
    //         DB::beginTransaction();

    //         $pembelian = PembelianBarang::findOrFail($id);

    //         $totalItem = 0;
    //         $totalNilai = 0;

    //         $counter = 1; // Nomor urut barang dalam pembelian

    //         foreach ($idBarangs as $index => $id_barang) {
    //             $qty = $qtys[$index] ?? null;
    //             $harga_barang = $hargaBarangs[$index] ?? null;

    //             if (is_null($qty) || is_null($harga_barang)) {
    //                 continue;
    //             }

    //             // Update DetailPembelianBarang
    //             if ($id_barang && $qty > 0 && $harga_barang > 0) {
    //                 $barang = Barang::findOrFail($id_barang);

    //                 // Generate QR Code Value
    //                 $tglNota = \Carbon\Carbon::parse($pembelian->tgl_nota)->format('dmY');
    //                 $idSupplier = $pembelian->id_supplier;         // ID Supplier
    //                 $idPembelian = $pembelian->id;                // ID Pembelian
    //                 $qrCodeValue = "{$tglNota}SP{$idSupplier}ID{$idPembelian}-{$counter}";

    //                 // Path QR code for this barang
    //                 $qrCodePath = "qrcodes/pembelian/{$idPembelian}-{$counter}.png";
    //                 $fullPath = storage_path('app/public/' . $qrCodePath);

    //                 // Buat folder jika belum ada
    //                 if (!file_exists(dirname($fullPath))) {
    //                     mkdir(dirname($fullPath), 0755, true);
    //                 }

    //                 // Generate QR Code
    //                 QrCode::size(200)->format('png')->generate($qrCodeValue, $fullPath);

    //                 $detail = DetailPembelianBarang::updateOrCreate(
    //                     [
    //                         'id_pembelian_barang' => $pembelian->id,
    //                         'id_barang' => $id_barang,
    //                     ],
    //                     [
    //                         'nama_barang' => $barang->nama_barang,
    //                         'qty' => $qty,
    //                         'harga_barang' => $harga_barang,
    //                         'total_harga' => $qty * $harga_barang,
    //                         'qrcode' => $qrCodeValue, // Simpan QR Code Value
    //                         'qrcode_path' => $qrCodePath, // Simpan Path QR Code
    //                     ]
    //                 );

    //                 // Update status menjadi success jika tidak ingin merubah field lain
    //                 $detail->status = 'success';
    //                 $detail->save();

    //                 $totalItem += $detail->qty;
    //                 $totalNilai += $detail->total_harga;

    //                 // Proses Level Harga
    //                 $levelHargaBarang = [];

    //                 if (isset($levelHargas[$id_barang]) && is_array($levelHargas[$id_barang])) {
    //                     foreach ($levelHargas[$id_barang] as $levelIndex => $hargaLevel) {
    //                         $levelNama = $levelNamas[$levelIndex] ?? 'Level ' . ($levelIndex + 1);
    //                         if (!is_null($hargaLevel)) {
    //                             $levelHargaBarang[] = "{$levelNama} : {$hargaLevel}";
    //                         }
    //                     }
    //                 }

    //                 // Simpan Level Harga sebagai JSON ke tabel Barang
    //                 $barang->level_harga = json_encode($levelHargaBarang);
    //                 $barang->save();

    //                 // Update atau Insert ke stockBarang
    //                 $stockBarang = StockBarang::firstOrNew(['id_barang' => $id_barang]);

    //                 $hpp_awal = $stockBarang->hpp_awal ?: $harga_barang;
    //                 $stock_awal = $stockBarang->stock ?: 0;

    //                 // Hitung nilai total dan hpp baru
    //                 $total_harga_barang = DetailPembelianBarang::where('id_barang', $id_barang)->sum('total_harga');
    //                 $total_qty_barang = DetailPembelianBarang::where('id_barang', $id_barang)->sum('qty');

    //                 if ($total_qty_barang > 0) {
    //                     $hpp_baru = $total_harga_barang / $total_qty_barang;
    //                 } else {
    //                     $hpp_baru = $hpp_awal;
    //                 }

    //                 // Update stock dan nilai total di stockBarang
    //                 $stockBarang->stock = $stock_awal + $detail->qty;
    //                 $stockBarang->hpp_awal = $hpp_awal;
    //                 $stockBarang->hpp_baru = $hpp_baru;
    //                 $stockBarang->nilai_total = $hpp_baru * $stockBarang->stock;
    //                 $stockBarang->nama_barang = $barang->nama_barang;
    //                 $stockBarang->save();

    //                 $counter++;
    //             }
    //         }

    //         // Update total item dan total nilai pembelian
    //         $pembelian->total_item = $totalItem;
    //         $pembelian->total_nilai = $totalNilai;
    //         $pembelian->status = 'success';
    //         $pembelian->save();

    //         DB::commit();

    //         return redirect()->route('transaksi.pembelianbarang.index')->with('success', 'Data berhasil disimpan');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return response()->json(['success' => false, 'message' => 'Failed to update pembelian barang. ' . $e->getMessage()]);
    //     }
    // }

    public function update(Request $request, $id)
    {
        $idBarangs = $request->input('id_barang', []);
        $qtys = $request->input('qty', []);
        $hargaBarangs = $request->input('harga_barang', []);
        $levelNamas = $request->input('level_nama', []);
        $levelHargas = $request->input('level_harga', []);

        try {
            DB::beginTransaction();

            $pembelian = PembelianBarang::findOrFail($id);

            $totalItem = 0;
            $totalNilai = 0;

            $counter = 1; // Nomor urut barang dalam pembelian

            foreach ($idBarangs as $index => $id_barang) {
                $qty = $qtys[$index] ?? null;
                $harga_barang = $hargaBarangs[$index] ?? null;

                if (is_null($qty) || is_null($harga_barang)) {
                    continue;
                }

                // Update DetailPembelianBarang
                if ($id_barang && $qty > 0 && $harga_barang > 0) {
                    $barang = Barang::findOrFail($id_barang);

                    // Generate QR Code Value
                    $tglNota = \Carbon\Carbon::parse($pembelian->tgl_nota)->format('dmY');
                    $idSupplier = $pembelian->id_supplier;
                    $idPembelian = $pembelian->id;
                    $qrCodeValue = "{$tglNota}SP{$idSupplier}ID{$idPembelian}-{$counter}";

                    // Path QR code for this barang
                    $qrCodePath = "qrcodes/pembelian/{$idPembelian}-{$counter}.png";
                    $fullPath = storage_path('app/public/' . $qrCodePath);

                    // Buat folder jika belum ada
                    if (!file_exists(dirname($fullPath))) {
                        mkdir(dirname($fullPath), 0755, true);
                    }

                    // Generate QR Code dengan endroid/qr-code
                    $qrCode = QrCode::create($qrCodeValue)
                        ->setEncoding(new Encoding('UTF-8'))
                        ->setSize(200)
                        ->setMargin(10);

                    $writer = new PngWriter();
                    $result = $writer->write(
                        $qrCode,
                        null,
                        Label::create("{$barang->nama_barang}")
                            ->setFont(new NotoSans(12))
                    );

                    // Simpan file QR Code
                    $result->saveToFile($fullPath);

                    $detail = DetailPembelianBarang::updateOrCreate(
                        [
                            'id_pembelian_barang' => $pembelian->id,
                            'id_barang' => $id_barang,
                        ],
                        [
                            'nama_barang' => $barang->nama_barang,
                            'qty' => $qty,
                            'harga_barang' => $harga_barang,
                            'total_harga' => $qty * $harga_barang,
                            'qrcode' => $qrCodeValue, // Simpan QR Code Value
                            'qrcode_path' => $qrCodePath, // Simpan Path QR Code
                        ]
                    );

                    $detail->status = 'success';
                    $detail->save();

                    $totalItem += $detail->qty;
                    $totalNilai += $detail->total_harga;

                    // Proses Level Harga
                    $levelHargaBarang = [];

                    if (isset($levelHargas[$id_barang]) && is_array($levelHargas[$id_barang])) {
                        foreach ($levelHargas[$id_barang] as $levelIndex => $hargaLevel) {
                            $levelNama = $levelNamas[$levelIndex] ?? 'Level ' . ($levelIndex + 1);
                            if (!is_null($hargaLevel)) {
                                $levelHargaBarang[] = "{$levelNama} : {$hargaLevel}";
                            }
                        }
                    }

                    // Simpan Level Harga sebagai JSON ke tabel Barang
                    $barang->level_harga = json_encode($levelHargaBarang);
                    $barang->save();

                    // Update atau Insert ke stockBarang
                    $stockBarang = StockBarang::firstOrNew(['id_barang' => $id_barang]);

                    $hpp_awal = $stockBarang->hpp_awal ?: $harga_barang;
                    $stock_awal = $stockBarang->stock ?: 0;

                    $total_harga_barang = DetailPembelianBarang::where('id_barang', $id_barang)->sum('total_harga');
                    $total_qty_barang = DetailPembelianBarang::where('id_barang', $id_barang)->sum('qty');

                    $hpp_baru = $total_qty_barang > 0 ? $total_harga_barang / $total_qty_barang : $hpp_awal;

                    $stockBarang->stock = $stock_awal + $detail->qty;
                    $stockBarang->hpp_awal = $hpp_awal;
                    $stockBarang->hpp_baru = $hpp_baru;
                    $stockBarang->nilai_total = $hpp_baru * $stockBarang->stock;
                    $stockBarang->nama_barang = $barang->nama_barang;
                    $stockBarang->save();

                    $counter++;
                }
            }

            $pembelian->total_item = $totalItem;
            $pembelian->total_nilai = $totalNilai;
            $pembelian->status = 'success';
            $pembelian->save();

            DB::commit();

            return redirect()->route('transaksi.pembelianbarang.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Failed to update pembelian barang. ' . $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        // Ambil data pembelian
        $pembelian = PembelianBarang::findOrFail($id);

        $detail_ids = $request->input('detail_ids', []);
        $statuses = $request->input('status_detail', []);
        $level_nama = $request->input('level_nama', []);
        $level_hargas = $request->input('level_harga', []);

        foreach ($detail_ids as $key => $detail_id) {
            $detail = DetailPembelianBarang::findOrFail($detail_id);

            if (isset($statuses[$key]) && $statuses[$key] == 'failed') {
                // Update the status in detail pembelian to failed
                $detail->status = 'failed';
                $detail->save();
            }

            if (isset($statuses[$key]) && $statuses[$key] == 'success') {

                // Update the status in detail pembelian
                $detail->status = 'success';
                $detail->save();

                // Process the level harga data
                $levelHargaData = [];

                if (isset($level_hargas[$key])) {
                    foreach ($level_hargas[$key] as $index => $nilai) {
                        $namaLevel = $level_nama[$index]; // Nama level dari array level_nama
                        $levelHargaData[] = "{$namaLevel} : {$nilai}";
                    }
                }

                // Convert level harga array to JSON format
                $levelHargaJson = json_encode($levelHargaData);

                // Check if stock already exists
                $existingStock = StockBarang::where('id_barang', $detail->id_barang)->first();

                $barang = Barang::where('id', $detail->id_barang)->first();

                if ($existingStock) {
                    $successfulDetails = DetailPembelianBarang::where('id_barang', $detail->id_barang)
                        ->where('status', 'success')
                        ->get();

                    $totalHargaSemua = $successfulDetails->sum('total_harga');
                    $totalQtySemua = $successfulDetails->sum('qty');

                    // Hitung HPP baru
                    $hppBaru = $totalHargaSemua / $totalQtySemua;

                    $existingStock->stock += $detail->qty;
                    $existingStock->harga_satuan = $detail->harga_barang;
                    $existingStock->hpp_baru = $hppBaru;

                    $barang->level_harga = $levelHargaJson;
                    $barang->save();

                    $existingStock->save();
                } else {
                    // Insert new stock record
                    $newStock = new StockBarang();
                    $newStock->id_barang = $detail->id_barang;
                    $newStock->nama_barang = $detail->barang->nama_barang;
                    $newStock->harga_satuan = $detail->harga_barang;
                    $newStock->hpp_awal = $detail->harga_barang;
                    $newStock->hpp_baru = $detail->total_harga / $detail->qty;
                    $newStock->stock = $detail->qty;
                    $newStock->nilai_total = $detail->qty;

                    $barang->level_harga = $levelHargaJson;
                    $barang->save();

                    $newStock->save();
                }
            }
        }

        $hasFailed = $pembelian->detail()->where('status', 'failed')->count() > 0;
        $allSuccess = $pembelian->detail()->where('status', '!=', 'success')->count() === 0;

        if ($allSuccess) {
            $pembelian->status = 'success';
        } elseif ($hasFailed) {
            $pembelian->status = 'mixed';
        }

        $pembelian->save();

        return redirect()->route('transaksi.pembelianbarang.index')->with('success', 'Data berhasil disimpan');
    }


    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $pembelian = PembelianBarang::findOrFail($id);

            $pembelian->detail()->delete();

            $pembelian->delete();

            DB::commit();

            return redirect()->route('transaksi.pembelianbarang.index')
                ->with('success', 'Pembelian barang deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Failed to delete pembelian barang. ' . $e->getMessage());
        }
    }
}
