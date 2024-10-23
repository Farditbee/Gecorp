<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailToko;
use App\Models\Kasir;
use App\Models\LevelHarga;
use App\Models\Member;
use App\Models\StockBarang;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class KasirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kasir = Kasir::all();
        $barang = Barang::all();
        $detail_toko = DetailToko::all();
        $stock = StockBarang::all();
        $member = Member::all();
        $level_harga = LevelHarga::all();
        $toko = Toko::all();
        $user = Auth::user(); // Mengambil user yang sedang login

        if ($user->id_toko == 1) {
            // Ambil data dari tabel stock_barang
            $stock = StockBarang::with('barang')->get();
            $detail_toko = collect(); // Kosongkan jika tidak digunakan
        } else {
            // Ambil data dari detail_toko berdasarkan id_toko
            $stock = collect(); // Kosongkan jika tidak digunakan
            $detail_toko = DetailToko::where('id_toko', $user->id_toko)->get();
        }

        return view('transaksi.kasir.index', compact('kasir', 'barang', 'detail_toko','stock','member','user','level_harga', 'toko'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Kasir $kasir)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kasir $kasir)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kasir $kasir)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kasir $kasir)
    {
        //
    }
}
