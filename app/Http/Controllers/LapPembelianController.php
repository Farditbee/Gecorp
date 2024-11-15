<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LevelHarga;
use App\Models\PembelianBarang;
use App\Models\Supplier;
use Illuminate\Http\Request;

class LapPembelianController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query untuk mengambil data PembelianBarang dengan status 'success'
        $query = PembelianBarang::where('status', 'success')->orderBy('id', 'desc');

        // Cek apakah ada parameter tanggal yang dikirimkan
        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            // Lakukan filter berdasarkan tanggal
            $query->whereBetween('tgl_nota', [$startDate, $endDate]);

            // Ambil data pembelian yang sudah difilter
            $pembelian_dt = $query->get();
            $suppliers = Supplier::all();
            $barang = Barang::all();
            $LevelHarga = LevelHarga::all();

            // Kirim data ke view dengan filter tanggal
            return view('laporan.pembelian.index', compact('pembelian_dt', 'suppliers', 'barang', 'LevelHarga'))
                ->with('startDate', $startDate)
                ->with('endDate', $endDate);
        } else {
            // Jika tidak ada filter tanggal, ambil semua data
            $pembelian_dt = $query->get();
            $suppliers = Supplier::all();
            $barang = Barang::all();
            $LevelHarga = LevelHarga::all();

            // Kirim data ke view tanpa filter tanggal
            return view('laporan.pembelian.index', compact('pembelian_dt', 'suppliers', 'barang', 'LevelHarga'));
        }
    }
}
