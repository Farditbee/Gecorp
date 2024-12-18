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
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Laporan Pengiriman Barang',
        ];
    }

    public function index(Request $request)
    {
        $menu = [$this->title[0], $this->label[2]];
        $toko = collect(); // Inisialisasi koleksi kosong untuk data toko
        $barang = Barang::all();
        $user = User::all();
        $users = Auth::user();

        // Memeriksa apakah ada parameter startDate dan endDate pada request
        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            // Mengambil data toko yang terkait dengan pengiriman pada periode tertentu
            $toko = Toko::with(['pengirimanSebagaiPengirim' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tgl_kirim', [$startDate, $endDate]);
            }])->get();
        }

        return view('laporan.pengiriman.index', compact('menu', 'toko', 'barang', 'user', 'users'));
    }
}
