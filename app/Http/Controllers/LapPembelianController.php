<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LevelHarga;
use App\Models\PembelianBarang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LapPembelianController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Laporan Pembelian Barang',
        ];
    }

    public function index(Request $request)
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }
        $menu = [$this->title[0], $this->label[2]];
        $suppliers = Supplier::all();
        $barang = Barang::all();
        $LevelHarga = LevelHarga::all();

        // Default tanggal awal dan akhir untuk bulan ini
        $startDate = now()->startOfMonth()->toDateString();
        $endDate = now()->endOfMonth()->toDateString();

        // Jika parameter tanggal dikirimkan, gunakan tanggal dari request
        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
        }

        // Ambil data pembelian berdasarkan tanggal
        $pembelian_dt = PembelianBarang::where('status', 'success')
            ->whereBetween('tgl_nota', [$startDate, $endDate])
            ->orderBy('id', 'desc')
            ->get();

        // Jika tidak ada data pembelian di bulan ini dan tidak ada filter, tampilkan pesan
        if ($pembelian_dt->isEmpty() && !$request->has('startDate') && !$request->has('endDate')) {
            return view('laporan.pembelian.index', compact('menu', 'suppliers', 'barang', 'LevelHarga'))
                ->with('pembelian_dt', collect()) // Kirim koleksi kosong
                ->with('startDate', $startDate)
                ->with('endDate', $endDate)
                ->with('message', 'Tidak ada data di bulan ini.');
        }

        // Kirim data ke view
        return view('laporan.pembelian.index', compact('menu', 'pembelian_dt', 'suppliers', 'barang', 'LevelHarga'))
            ->with('startDate', $startDate)
            ->with('endDate', $endDate)
            ->with('message', null); // Tidak ada pesan jika ada data
    }
}
