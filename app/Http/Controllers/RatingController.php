<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailKasir;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Rating Barang',
        ];
    }

    public function index(Request $request)
{
    if (!in_array(Auth::user()->id_level, [1, 2])) {
        abort(403, 'Unauthorized');
    }
    $menu = [$this->title[0], $this->label[2]];
    $toko = Toko::all();
    $barang = Barang::all();

    $selectedTokoIds = $request->input('toko_select', []); // Ambil toko yang dipilih dari request
    $dataBarang = collect();

    if (!empty($selectedTokoIds)) {
        // Filter berdasarkan toko yang dipilih
        $dataBarang = DetailKasir::select('detail_kasir.id_barang', 'kasir.id_toko', DB::raw('SUM(detail_kasir.qty) as total_terjual'))
            ->join('kasir', 'detail_kasir.id_kasir', '=', 'kasir.id')
            ->whereIn('kasir.id_toko', $selectedTokoIds)
            ->groupBy('detail_kasir.id_barang', 'kasir.id_toko')
            ->get()
            ->groupBy('id_barang'); // Grupkan data berdasarkan id_barang
    } else {
        // Tampilkan data untuk semua toko
        $dataBarang = DetailKasir::select('detail_kasir.id_barang', 'kasir.id_toko', DB::raw('SUM(detail_kasir.qty) as total_terjual'))
            ->join('kasir', 'detail_kasir.id_kasir', '=', 'kasir.id')
            ->groupBy('detail_kasir.id_barang', 'kasir.id_toko')
            ->get()
            ->groupBy('id_barang'); // Grupkan data berdasarkan id_barang
    }

    // Kirim data ke view
    return view('laporan.rating.index', compact('menu', 'barang', 'toko', 'dataBarang', 'selectedTokoIds'));
}

public function getBarangJual(Request $request)
{
    $selectedTokoIds = $request->input('toko_select', []); // Ambil toko yang dipilih dari request

    if (!empty($selectedTokoIds)) {
        // Filter berdasarkan toko yang dipilih
        $dataBarang = DetailKasir::select('detail_kasir.id_barang', 'kasir.id_toko', DB::raw('SUM(detail_kasir.qty) as total_terjual'))
            ->join('kasir', 'detail_kasir.id_kasir', '=', 'kasir.id')
            ->whereIn('kasir.id_toko', $selectedTokoIds)
            ->groupBy('detail_kasir.id_barang', 'kasir.id_toko')
            ->get()
            ->groupBy('id_barang'); // Grupkan data berdasarkan id_barang
    } else {
        // Tampilkan data untuk semua toko
        $dataBarang = DetailKasir::select('detail_kasir.id_barang', 'kasir.id_toko', DB::raw('SUM(detail_kasir.qty) as total_terjual'))
            ->join('kasir', 'detail_kasir.id_kasir', '=', 'kasir.id')
            ->groupBy('detail_kasir.id_barang', 'kasir.id_toko')
            ->get()
            ->groupBy('id_barang'); // Grupkan data berdasarkan id_barang
    }

    return response()->json($dataBarang);
}

}
