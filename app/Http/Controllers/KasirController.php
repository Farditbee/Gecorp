<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailToko;
use App\Models\Kasir;
use App\Models\LevelHarga;
use App\Models\Member;
use App\Models\StockBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KasirController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kasir = Kasir::all();

        if($user->id_level == 1){
            $barang = StockBarang::all();
            $member = Member::all();
        }else{
            $barang = DetailToko::where('id_toko', $user->id_toko)->get();
            $member = Member::where('id_toko', $user->id_toko)->get();
        }

        return view('transaksi.kasir.index', compact('barang', 'kasir', 'member'));
    }

    public function getFilteredHarga(Request $request)
    {
        $memberId = $request->input('id_member');
        $barangId = $request->input('id_barang');

        // Ambil data barang
        $barang = Barang::find($barangId);
        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan.'], 404);
        }

        // Parsing level harga barang jika dalam bentuk JSON string
        $levelHarga = is_string($barang->level_harga) ? json_decode($barang->level_harga, true) : $barang->level_harga;

        // Cek apakah member adalah Guest
        if ($memberId === 'Guest') {
            // Urutkan semua level harga barang dari tertinggi ke terendah
            $filteredHarga = collect($levelHarga)
                ->sortByDesc(function ($harga) {
                    // Ekstrak nilai harga dari string untuk pengurutan numerik
                    return (int)explode(' : ', $harga)[1];
                })
                ->values()
                ->map(function ($harga) {
                    return intval(explode(' : ', $harga)[1]); // Hanya ambil nilai harga
                });

            return response()->json(['filteredHarga' => $filteredHarga]);
        }

        // Lanjutkan dengan logika normal jika bukan Guest
        $member = Member::find($memberId);
        if (!$member) {
            return response()->json(['error' => 'Member tidak ditemukan.'], 404);
        }

        // Parsing level_info jika dalam bentuk JSON string
        $levelInfo = is_string($member->level_info) ? json_decode($member->level_info, true) : $member->level_info;
        $jenisBarangId = $barang->id_jenis_barang;

        // Ambil ID level yang cocok dengan jenis barang dari level_info
        $levelIds = collect($levelInfo)->map(function ($info) use ($jenisBarangId) {
            list($infoJenisBarangId, $infoLevelId) = explode(' : ', $info);
            return intval($infoJenisBarangId) === intval($jenisBarangId) ? intval($infoLevelId) : null;
        })->filter();

        // Ambil nama level harga yang sesuai dari tabel LevelHarga
        $levelNames = LevelHarga::whereIn('id', $levelIds)->pluck('nama_level_harga');

        // Filter level harga barang sesuai dengan levelNames
        $filteredHarga = collect($levelHarga)->filter(function ($harga) use ($levelNames) {
            return $levelNames->contains(function ($levelName) use ($harga) {
                return str_contains($harga, $levelName);
            });
        })->map(function ($harga) {
            return intval(explode(' : ', $harga)[1]); // Ambil hanya angka dari harga
        })->values();

        Log::info('Filtered Harga:', ['filteredHarga' => $filteredHarga->toArray()]);

        // Mengatur respons untuk mengembalikan angka jika hanya satu elemen
        $response = count($filteredHarga) === 1 ? $filteredHarga->first() : $filteredHarga;

        return response()->json(['filteredHarga' => $response]);
    }

}
