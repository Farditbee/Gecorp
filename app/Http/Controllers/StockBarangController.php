<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPembelianBarang;
use App\Models\DetailToko;
use App\Models\StockBarang;
use App\Models\Toko;
use Illuminate\Http\Request;

class StockBarangController extends Controller
{
    public function index()
    {
        $stock = StockBarang::orderBy('id', 'desc')->get();
        $stocks = StockBarang::all();

    return view('master.stockbarang.index', compact('stock', 'stocks'));
    }

    public function detail(string $id)
    {
        $stock = StockBarang::findOrFail($id);

        return view('master.stockbarang.index', compact('stock'));
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
                'hpp_awal' => $stockBarang->hpp_awal,
                'hpp_baru' => $hppBaru,
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
            ]);
        }
    }

}
