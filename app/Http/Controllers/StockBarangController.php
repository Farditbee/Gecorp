<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPembelianBarang;
use App\Models\DetailStockBarang;
use App\Models\DetailToko;
use App\Models\LevelHarga;
use App\Models\StockBarang;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockBarangController extends Controller
{
    public function index()
    {
        // Mengambil stock barang beserta relasi ke barang dan toko
        $stock = StockBarang::with(['barang', 'toko'])
                            ->orderBy('id', 'desc')
                            ->get();

                            // Ambil stok barang dari tabel 'detail_toko' untuk semua toko kecuali id = 1
        $stokTokoLain = DetailToko::with('barang', 'toko')
                            ->where('id_toko', '!=', 1)
                            ->get();

        // Ambil semua toko
        $toko = Toko::all();
        $levelharga = LevelHarga::all();
        $barang = Barang::all();

        return view('master.stockbarang.index', compact('stock', 'stokTokoLain', 'toko', 'levelharga', 'barang'));
    }

    public function getItem($id_barang)
    {
        $item = StockBarang::where('id_barang', $id_barang)->first();

        // $detail = DetailStockBarang::where('id_barang', $id_barang)->get();

        // Jika ditemukan, kembalikan respons JSON
        if ($item) {
            return response()->json([
                'nama_barang' => $item->nama_barang
            ]);
        } else {
            return response()->json(['error' => 'Item not found'], 404);
        }
    }

    public function create()
    {
        return view('master.stockbarang.create');
    }

    public function getStockDetails($id_barang)
    {
        // Ambil data stock barang yang sesuai
        $stockBarang = StockBarang::where('id_barang', $id_barang)->first();

        $barang = Barang::where('id', $id_barang)->first();

        // Ambil semua detail pembelian dengan status 'success' untuk barang tersebut
        $successfulDetails = DetailPembelianBarang::where('id_barang', $id_barang)->get();

        $hpp0 = $successfulDetails->sum('harga_barang');

        // Hitung total harga dan total qty dari pembelian yang sudah 'success'
        $totalHargaSuccess = $successfulDetails->sum('total_harga');
        $totalQtySuccess = $successfulDetails->sum('qty');

        // Hitung HPP baru
        if ($totalQtySuccess > 0) {
            $hppBaru = $totalHargaSuccess / $totalQtySuccess;
        } else {
            $hppBaru = 0;
        }

        $level_harga = [];
        if ($barang && $barang->level_harga) {
            $decoded_level_harga = json_decode($barang->level_harga, true);
            foreach ($decoded_level_harga as $item) {
                list($level_name, $level_value) = explode(' : ', $item);
                $level_harga[$level_name] = $level_value;
            }
        }

        if ($stockBarang) {
            return response()->json([
                'stock' => $stockBarang->stock,
                'hpp_awal' => $hppBaru,
                'hpp_baru' => 0,
                'total_harga_success' => $totalHargaSuccess,
                'total_qty_success' => $totalQtySuccess,
                'level_harga' => $level_harga,
            ]);
        } else {
            return response()->json([
                'stock' => 0,
                'hpp_awal' => 0,
                'hpp_baru' => $hpp0,
                'total_harga_success' => $totalHargaSuccess,
                'total_qty_success' => $totalQtySuccess,
                'level_harga' => [],
            ]);
        }
    }

    public function updateLevelHarga(Request $request)
{
    $id_barang = $request->input('id_barang'); // Mengambil ID barang dari request

    try {
        DB::beginTransaction();

        // Ambil data barang berdasarkan ID
        $barang = Barang::findOrFail($id_barang);

        // Ambil semua level harga yang dikirim dari form
        $levelNamas = $request->input('level_nama', []);
        $levelHargas = $request->input('level_harga', []);

        $levelHargaBarang = [];

        // Loop untuk memperbarui level harga berdasarkan input dari form
        foreach ($levelHargas as $index => $hargaLevel) {
            $levelNama = $levelNamas[$index] ?? 'Level ' . ($index + 1);

            // Jika harga level tidak kosong, masukkan ke array level harga
            if (!is_null($hargaLevel)) {
                $levelHargaBarang[] = "{$levelNama} : {$hargaLevel}";
            }
        }

        // Simpan level harga yang baru dalam format JSON
        $barang->level_harga = json_encode($levelHargaBarang);
        $barang->save(); // Simpan perubahan ke database

        DB::commit(); // Commit transaksi jika semuanya berhasil

        return redirect()->back()->with('success', 'Level harga berhasil diperbarui');
    } catch (\Exception $e) {
        DB::rollback(); // Rollback jika ada error
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

}
