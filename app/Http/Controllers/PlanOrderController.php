<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailToko;
use App\Models\StockBarang;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanOrderController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Data Barang',
            'Tambah Data',
            'Edit Data'
        ];
    }

    public function getplanorder(Request $request)
{
    // Ambil id_toko dari request, jika kosong gunakan semua toko
    $selectedTokoIds = $request->input('id_toko', []);

    // Ambil semua toko
    $toko = Toko::all();

    // Jika tidak ada toko yang dipilih, gunakan semua id_toko
    if (empty($selectedTokoIds)) {
        $selectedTokoIds = $toko->pluck('id')->toArray();
    }

    // Mengambil stok barang dari StockBarang untuk toko yang dipilih
    $stock = StockBarang::with('barang', 'toko')
        ->whereIn('id_toko', $selectedTokoIds)
        ->orderBy('id', 'desc')
        ->get();

    // Mengambil stok barang dari DetailToko untuk toko selain id = 1 dan yang dipilih
    $stokTokoLain = DetailToko::with('barang', 'toko')
        ->whereIn('id_toko', $selectedTokoIds)
        ->where('id_toko', '!=', 1)
        ->get();

    // Ambil semua barang
    $barang = Barang::all();

    // Format data stok untuk respons JSON
    $data = $barang->map(function ($brg) use ($stock, $stokTokoLain, $selectedTokoIds, $toko) {
        $stokPerToko = $toko->mapWithKeys(function ($tk) use ($brg, $stock, $stokTokoLain, $selectedTokoIds) {
            if (in_array($tk->id, $selectedTokoIds)) {
                if ($tk->id == 1) {
                    // Ambil stok dari StockBarang untuk toko id = 1
                    $stok = $stock->where('id_barang', $brg->id)->where('id_toko', $tk->id)->first()?->stock ?? 0;
                } else {
                    // Ambil qty dari DetailToko untuk toko selain id = 1
                    $stok = $stokTokoLain->where('id_barang', $brg->id)->where('id_toko', $tk->id)->first()?->qty ?? 0;
                }
                return [$tk->singkatan => $stok];
            }
            return [];
        });

        return [
            'nama_barang' => $brg->nama_barang,
            'stok_per_toko' => $stokPerToko,
        ];
    });

    // Kembalikan data dalam format JSON
    return response()->json([
        "error" => false,
        "message" => $data->isEmpty() ? "No data found" : "Data retrieved successfully",
        "status_code" => 200,
        "data" => $data
    ]);
}


    public function index()
    {
        $menu = [$this->title[0], $this->label[0]];
        $user = Auth::user(); // Mendapatkan user yang sedang login

        // Mengambil stock barang beserta relasi ke barang dan toko
        $stock = StockBarang::with('barang', 'toko')
            ->orderBy('id', 'desc')
            ->get();

        // Ambil stok barang dari tabel 'detail_toko' untuk semua toko kecuali id = 1
        $stokTokoLain = DetailToko::with('barang', 'toko')
            ->where('id_toko', '!=', 1)
            ->get();

        // Ambil semua toko
        $toko = Toko::all();
        $barang = Barang::all();

        return view('master.planorder.index', compact('menu', 'stock', 'stokTokoLain', 'toko', 'barang'));
    }
}
