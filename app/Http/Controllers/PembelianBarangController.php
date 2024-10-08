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

class PembelianBarangController extends Controller
{
    public function index()
    {
        $pembelian_dt = PembelianBarang::orderBy('id', 'desc')->get();
        $suppliers = Supplier::all();
        $barang = Barang::all();
        $suppliers = Supplier::all();
        $LevelHarga = LevelHarga::all();
        return view('transaksi.pembelianbarang.index', compact('pembelian_dt', 'suppliers', 'barang', 'LevelHarga'));
    }

    public function create()
    {
        $barang = Barang::all();
        $suppliers = Supplier::all();
        $LevelHarga = LevelHarga::all();

        return view('transaksi.pembelianbarang.create', compact('suppliers', 'barang', 'LevelHarga'));
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

            // // Cek apakah request dari AJAX
            // if ($request->ajax()) {
            //     return response()->json(['success' => true, 'message' => 'Pembelian berhasil disimpan', 'pembelian' => $pembelian]);
            // }

            return redirect()->route('master.pembelianbarang.index')
                            ->with('tab', 'detail')
                            ->with('pembelian', $pembelian);

        } catch (\Exception $e) {
            DB::rollBack();

            // Cek apakah request dari AJAX
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pembelian = PembelianBarang::with('detail')->findOrFail($id);
        $LevelHarga = LevelHarga::all();

        return view('transaksi.pembelianbarang.edit', compact('pembelian', 'LevelHarga'));
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
            // dd($request->all());
            DB::beginTransaction();

            $pembelian = PembelianBarang::findOrFail($id);

            $totalItem = 0;
            $totalNilai = 0;

            foreach ($idBarangs as $index => $id_barang) {
                $qty = $qtys[$index] ?? null;
                $harga_barang = $hargaBarangs[$index] ?? null;

                if (is_null($qty) || is_null($harga_barang)) {
                    continue;
                }

                // Update DetailPembelianBarang
                if ($id_barang && $qty > 0 && $harga_barang > 0) {
                    $barang = Barang::findOrFail($id_barang);

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
                        ]
                    );

                    // Update status menjadi success jika tidak ingin merubah field lain
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

                    // Hitung nilai total dan hpp baru
                    $total_harga_barang = DetailPembelianBarang::where('id_barang', $id_barang)->sum('total_harga');
                    $total_qty_barang = DetailPembelianBarang::where('id_barang', $id_barang)->sum('qty');

                    if ($total_qty_barang > 0) {
                        $hpp_baru = $total_harga_barang / $total_qty_barang;
                    } else {
                        $hpp_baru = $hpp_awal;
                    }

                    // Update stock dan nilai total di stockBarang
                    $stockBarang->stock = $stock_awal + $detail->qty;
                    $stockBarang->hpp_awal = $hpp_awal;
                    $stockBarang->hpp_baru = $hpp_baru;
                    $stockBarang->nilai_total = $hpp_baru * $stockBarang->stock;
                    $stockBarang->nama_barang = $barang->nama_barang;
                    $stockBarang->save();
                }
            }

            // Update total item dan total nilai pembelian
            $pembelian->total_item = $totalItem;
            $pembelian->total_nilai = $totalNilai;
            $pembelian->status = 'success';
            $pembelian->save();

            DB::commit();

            return redirect()->route('master.pembelianbarang.index')->with('success', 'Data berhasil disimpan');
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

        return redirect()->route('master.pembelianbarang.index')->with('success', 'Data berhasil disimpan');
    }


    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $pembelian = PembelianBarang::findOrFail($id);

            $pembelian->detail()->delete();

            $pembelian->delete();

            DB::commit();

            return redirect()->route('master.pembelianbarang.index')
                             ->with('success', 'Pembelian barang deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Failed to delete pembelian barang. ' . $e->getMessage());
        }
    }

}
