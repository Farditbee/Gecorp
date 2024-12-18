<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LevelHarga;
use App\Models\PembelianBarang;
use App\Models\Supplier;
use Illuminate\Http\Request;

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
        $menu = [$this->title[0], $this->label[2]];
        // Pastikan semua data supplier, barang, dan LevelHarga tetap dikirim ke view
        $suppliers = Supplier::all();
        $barang = Barang::all();
        $LevelHarga = LevelHarga::all();

        // Cek apakah parameter tanggal dikirimkan
        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            // Ambil data pembelian yang sudah difilter
            $pembelian_dt = PembelianBarang::where('status', 'success')
                ->whereBetween('tgl_nota', [$startDate, $endDate])
                ->orderBy('id', 'desc')
                ->get();

            // Kirim data ke view dengan filter tanggal
            return view('laporan.pembelian.index', compact('menu', 'pembelian_dt', 'suppliers', 'barang', 'LevelHarga'))
                ->with('startDate', $startDate)
                ->with('endDate', $endDate);
        }

        // Jika tidak ada filter tanggal, kirim view tanpa data pembelian
        return view('laporan.pembelian.index', compact('menu', 'suppliers', 'barang', 'LevelHarga'))
            ->with('pembelian_dt', collect()) // Kirim koleksi kosong
            ->with('startDate', null)
            ->with('endDate', null);
    }
}
