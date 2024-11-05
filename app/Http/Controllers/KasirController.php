<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailKasir;
use App\Models\DetailToko;
use App\Models\Kasir;
use App\Models\LevelHarga;
use App\Models\Member;
use App\Models\StockBarang;
use App\Models\Toko;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KasirController extends Controller
{
    public function cetakStruk($idKasir) {
        $kasir = Kasir::with('detailKasir')->find($idKasir);
        return view('transaksi.kasir.cetak_struk', compact('kasir'));
    }

    public function index()
    {
        $user = Auth::user();
        $users = User::all();
        $detail_kasir = DetailKasir::all();
        $toko = Toko::all();

        if ($user->id_level == 1) {
            $kasir = Kasir::orderBy('id', 'desc')
                        ->get();
        } else {
            $kasir = Kasir::where('id_toko', $user->id_toko)
                        ->orderBy('id', 'desc')
                        ->get();
        }

        if($user->id_level == 1){
            $barang = StockBarang::all();
            $member = Member::all();
        }else{
            $barang = DetailToko::where('id_toko', $user->id_toko)->get();
            $member = Member::where('id_toko', $user->id_toko)->get();
        }

        return view('transaksi.kasir.index', compact('barang', 'kasir', 'member', 'detail_kasir', 'users', 'toko'));
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

    public function store(Request $request)
    {
        // dd($request->all());

        $idBarangs = $request->input('id_barang', []);
        $qtys = $request->input('qty', []);
        $hargaBarangs = $request->input('harga', []);

        // dd($idBarangs, $qtys, $hargaBarangs);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            $kasir = new Kasir();
            $kasir->id_member = $request->id_member == 'Guest' ? 0 : $request->id_member;
            $kasir->id_users = $user->id;
            $kasir->tgl_transaksi = now();
            $kasir->id_toko = $user->id_toko;
            $kasir->total_item = 0; // Nilai awal untuk mencegah error
            $kasir->total_nilai = 0; // Nilai awal untuk mencegah error
            $kasir->no_nota = $request->no_nota;
            $kasir->metode = $request->metode;
            $kasir->jml_bayar = $request->jml_bayar;
            $kasir->kembalian = $request->kembalian;
            $kasir->save();

            // // Debugging untuk memastikan $kasir->id telah ada
            // if (is_null($kasir->id)) {
            //     DB::rollback();
            //     return response()->json(['success' => false, 'message' => 'Failed to save Kasir data: id_kasir is null.']);
            // }

            $totalItem = 0;
            $totalNilai = 0;

            if ($request->has('id_barang')) {
                foreach ($idBarangs as $index => $id_barang) {
                    $qty = $qtys[$index] ?? null;
                    $harga_barang = $hargaBarangs[$index] ?? null;

                    if (is_null($qty) || is_null($harga_barang)) {
                        continue;
                    }

                    if ($id_barang && $qty > 0 && $harga_barang > 0) {

                        $detail = DetailKasir::updateOrCreate(
                            [
                                'id_kasir' => $kasir->id,
                                'id_barang' => $id_barang,
                            ],
                            [
                                'qty' => $qty,
                                'harga' => $harga_barang,
                                'total_harga' => $qty * $harga_barang,
                            ]
                        );

                        $totalItem += $detail->qty;
                        $totalNilai += $detail->total_harga;

                        // Pengurangan stok berdasarkan kondisi id_toko
                        if ($user->id_toko == 1) {
                            // Kurangi stok di tabel StockBarang
                            $stock = StockBarang::where('id_barang', $id_barang)->first();
                            if ($stock) {
                                $stock->stock -= $qty;
                                $stock->save();
                            }
                        } else {
                            // Kurangi stok di tabel DetailToko sesuai id_toko
                            $detailToko = DetailToko::where('id_barang', $id_barang)
                                ->where('id_toko', $user->id_toko)
                                ->first();
                            if ($detailToko) {
                                $detailToko->qty -= $qty;
                                $detailToko->save();
                            }
                        }

                    }
                }
            }

            $kasir->total_item = $totalItem;
            $kasir->total_nilai = $totalNilai;
            $kasir->save();

            DB::commit();

            return redirect()->route('master.kasir.index')->with('success', 'Data berhasil disimpan');

        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Failed to update pembelian barang. ' . $th->getMessage()]);
        }
    }

}
