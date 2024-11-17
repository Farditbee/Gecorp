<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PengirimanBarang;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LapPengirimanController extends Controller
{
    public function index(Request $request)
    {
        $toko = Toko::all();
        $barang = Barang::all();
        $user = User::all();
        $users = Auth::user();

        // Memeriksa apakah ada parameter `start_date` dan `end_date` pada request
        $query = PengirimanBarang::query();

        if ($users->id_level == 1) {
            // Jika user dengan id_level 1, dapat melihat semua data
            $query = $query->orderBy('id', 'desc');
        } else {
            // Jika level user bukan 1, hanya tampilkan data toko terkait
            $query = $query->where('toko_penerima', $users->id_toko)
                           ->orWhere('toko_pengirim', $users->id_toko)
                           ->orderBy('id', 'desc');
        }

        // Menerapkan filter tanggal jika parameter `start_date` dan `end_date` ada
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $query = $query->whereBetween('tgl_kirim', [$startDate, $endDate]);
        }

        $pengiriman_barang = $query->get();

        return view('laporan.pengiriman.index', compact('toko', 'barang', 'user', 'pengiriman_barang', 'users'));
    }
}
