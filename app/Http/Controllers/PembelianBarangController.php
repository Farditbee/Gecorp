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
        $pembelian = PembelianBarang::orderBy('id', 'desc')->get();
        return view('transaksi.pembelianbarang.index', compact('pembelian'));
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

            return redirect()->route('master.pembelianbarang.create')
                             ->with('tab', 'detail')
                             ->with('pembelian', $pembelian);

        } catch (\Exception $e) {
            DB::rollBack();

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
        if ($stock && $stock->level_harga) {
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

        foreach ($idBarangs as $index => $id_barang) {
            $qty = $qtys[$index] ?? null;
            $harga_barang = $hargaBarangs[$index] ?? null;

            if (is_null($qty) || is_null($harga_barang)) {
                continue;
            }

            if ($qty <= 0 || $harga_barang <= 0) {
                return redirect()->back()->with('error', 'Failed: Data harap diisi dengan benar.');
            }
        }

        try {
            DB::beginTransaction();

            $pembelian = PembelianBarang::findOrFail($id);

            $totalItem = 0;
            $totalNilai = 0;

            $count = count($idBarangs);
            for ($i = 0; $i < $count; $i++) {
                $id_barang = $idBarangs[$i];
                $qty = $qtys[$i] ?? null;
                $harga_barang = $hargaBarangs[$i] ?? null;

                if (is_null($qty) || is_null($harga_barang)) {
                    continue;
                }

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

                    $totalItem += $detail->qty;
                    $totalNilai += $detail->total_harga;
                }
            }

            $pembelian->total_item = $totalItem;
            $pembelian->total_nilai = $totalNilai;
            $pembelian->save();

            DB::commit();

            return redirect()->route('master.pembelianbarang.index')->with('success', 'Data Pembelian Barang berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Failed to update pembelian barang. ' . $e->getMessage());
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

                if ($existingStock) {
                    $successfulDetails = DetailPembelianBarang::where('id_barang', $detail->id_barang)
                                                                ->where('status', 'success')
                                                                ->get();
                    // dd($successfulDetails);
                    // Hitung total harga dan qty dari pembelian yang sudah 'success'
                    $totalHargaSemua = $successfulDetails->sum('total_harga');
                    $totalQtySemua = $successfulDetails->sum('qty');

                    // if ($totalQtyBaru > 0) {
                    //     $hppBaru = $totalHargaBaru / $totalQtyBaru;
                    // }
                    // else{
                    //     $hppBaru = 0;
                    // }
                    // Hitung HPP baru
                    $hppBaru = $totalHargaSemua / $totalQtySemua;

                    $existingStock->stock += $detail->qty;
                    $existingStock->harga_satuan = $detail->harga_barang;
                    $existingStock->hpp_baru = $hppBaru;
                    $existingStock->level_harga = $levelHargaJson;
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
                    $newStock->level_harga = $levelHargaJson;
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
