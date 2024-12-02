<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailKasir;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        $toko = Toko::all(); // Ambil semua toko
        $barang = Barang::all(); // Ambil semua barang

        // Ambil toko yang dipilih dari request
        $selectedTokoIds = $request->input('toko_select', []); // Ambil toko yang dipilih, defaultnya kosong

        // Inisialisasi koleksi untuk data barang terjual
        $dataBarang = collect();

        if (!empty($selectedTokoIds)) {
            // Jika ada toko yang dipilih, kita ambil jumlah barang yang terjual per toko
            $dataBarang = DetailKasir::select('id_barang', 'id_toko', DB::raw('SUM(jumlah) as total_terjual'))
                ->whereIn('id_toko', $selectedTokoIds) // Filter berdasarkan toko yang dipilih
                ->groupBy('id_barang', 'id_toko')
                ->get();
        }

        return view('laporan.rating.index', compact('barang', 'toko', 'dataBarang', 'selectedTokoIds'));
    }

}
