<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailToko;
use App\Models\LevelHarga;
use App\Models\StockBarang;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TokoController extends Controller
{

    public function index(Request $request)
    {
        $toko = Toko::orderBy('id', 'desc')->get();
        $levelharga = LevelHarga::all();
        // Kembalikan ke view dengan variabel $toko yang berisi hasil pagination
        return view('master.toko.index', compact('toko','levelharga'));
    }


    public function create()
    {
        $levelharga = LevelHarga::all();
        return view('master.toko.create', compact('levelharga'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_toko' => 'required|max:255',
            'id_level_harga' => 'required|array', // Validasi sebagai array
            'wilayah' => 'required|max:255',
            'alamat' => 'required|max:255',
        ],[
            'nama_toko.required' => 'Nama Toko tidak boleh kosong.',
            'id_level_harga.required' => 'Level Harga tidak boleh kosong.',
            'wilayah.required' => 'Wilayah tidak boleh kosong.',
            'alamat.required' => 'Alamat tidak boleh kosong.',
        ]);

        try {
            // Simpan data Toko
            Toko::create([
                'nama_toko' => $request->nama_toko,
                'wilayah' => $request->wilayah,
                'alamat' => $request->alamat,
                'id_level_harga' => json_encode($request->id_level_harga), // Menyimpan array sebagai JSON
            ]);

            return redirect()->route('master.toko.index')->with('success', 'Sukses menambahkan Toko Baru');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }


    public function detail(string $id)
    {
        $toko = Toko::findOrFail($id);

        $levelHargaArray = json_decode($toko->id_level_harga, true) ?? [];

        // Jika hanya satu id disimpan, pastikan dia array
        if (is_int($levelHargaArray)) {
            $levelHargaArray = [$levelHargaArray];
        }

        // Ambil data level harga berdasarkan id yang ada di array
        $levelhargas = [];
        if (is_array($levelHargaArray) && !empty($levelHargaArray)) {
            $levelhargas = LevelHarga::whereIn('id', $levelHargaArray)->get();
        }

        // dd($toko->levelharga());

        $detail_toko = DetailToko::where('id_toko', $id)
                   ->with('barang')
                   ->orderBy('id', 'desc')
                   ->get();

        $stock = StockBarang::orderBy('id', 'desc')->get();

        return view('master.toko.detail', compact('toko', 'detail_toko', 'stock', 'levelhargas'));
    }
    // public function getHargaBarang($id_barang, $id_detail, $id_toko)
    // {
    //     $detailToko = DetailToko::where('id', $id_detail)
    //                             ->where('id_barang', $id_barang)
    //                             ->where('id_toko', $id_toko)
    //                             ->first(['harga']);
    //     // dd($detailToko);
    //     if (!$detailToko) {
    //         return response()->json(['error' => 'No price found'], 404);
    //     }

    //     return response()->json(['harga' => $detailToko->harga]);
    // }

    public function create_detail(string $id)
    {
        $toko = Toko::findOrFail($id);
        $barang = Barang::all();
        // $levelharga = LevelHarga::all();
        return view('master.toko.create_detail', ['id_toko' => $toko->id], compact('barang', 'toko'));
    }

    public function store_detail(Request $request)
    {
        $validatedData = $request->validate([
            'id_barang' => 'required|max:255',
            'stock' => 'required|max:225', // Validasi sebagai array
            'harga' => 'required|max:255',
        ],[
            'id_barang.required' => 'Nama Barang tidak boleh kosong.',
            'stock.required' => 'Stock Barang tidak boleh kosong.',
            'harga.required' => 'Harga tidak boleh kosong.',
        ]);

        try {
            $harga = str_replace(',', '', $request->harga);
            $id_toko = $request->input('id_toko');
            $toko = Toko::findOrFail($id_toko);
            // Simpan data Toko
            DetailToko::create([
                'id_toko' => $id_toko,
                'id_barang' => $request->id_barang,
                'stock' => $request->stock,
                'harga' => $harga,
            ]);

            return redirect()->route('master.toko.detail', ['id' => $toko->id])->with('success', 'Berhasil menambahkan Barang Baru');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        $levelharga = LevelHarga::all();
        $toko = Toko::findOrFail($id);
        return view('master.toko.edit', compact('toko', 'levelharga'));
    }

    public function edit_detail(string $id_toko, $id_barang, $id)
    {
        $toko = Toko::findOrFail($id_toko);
        $detail_toko = DetailToko::where('id', $id)
                                ->where('id_toko', $id_toko)
                                ->where('id_barang', $id_barang)
                                ->firstOrFail(); // Ambil data toko berdasarkan ID
        $barang = Barang::all(); // Cari barang berdasarkan ID dan ID toko
        // dd($detail_toko);
        return view('master.toko.edit_detail', compact('toko', 'barang', 'detail_toko'));
    }

    public function update(Request $request, string $id)
    {
        $toko = Toko::findOrFail($id);
        try {
            $toko->update([
                'nama_toko' => $request->nama_toko,
                'wilayah' => $request->wilayah,
                'alamat' => $request->alamat,
                'id_level_harga' => json_encode($request->id_level_harga),
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
        return redirect()->route('master.toko.index')->with('success', 'Sukses Mengubah Data Toko');
    }

    public function update_detail(Request $request, string $id_toko, string $id_barang){
        $validatedData = $request->validate([
            'id_barang' => 'required|max:255',
            'stock' => 'required|numeric', // Validasi sebagai array
            'harga' => 'required|max:255',
        ],[
            'id_barang.required' => 'Nama Barang tidak boleh kosong.',
            'stock.required' => 'Stock Barang tidak boleh kosong.',
            'harga.required' => 'Harga tidak boleh kosong.',
        ]);

        try {
            $toko = Toko::findOrFail($id_toko);
            $harga = str_replace(',', '', $request->harga);
            $detail_toko = DetailToko::where('id_toko', $id_toko)
            ->where('id_barang', $id_barang)
            ->firstOrFail();
            // Update data Toko
            $detail_toko->update([
                'id_toko' => $id_toko,
                'id_barang' => $request->id_barang,
                'stock' => $request->stock,
                'harga' => $harga,
            ]);

            return redirect()->route('master.toko.detail', ['id' => $toko->id])->with('success', 'Berhasil mengupdate Barang Baru');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete_detail(string $id_toko, string $id_barang)
    {
        try {
            $toko = Toko::findOrFail($id_toko);
            $detail_toko = DetailToko::where('id_toko', $id_toko)
            ->where('id_barang', $id_barang)
            ->firstOrFail();
            // Hapus data Barang
            $detail_toko->delete();

            return redirect()->route('master.toko.detail', ['id' => $toko->id])->with('success', 'Berhasil Menghapus Data Barang di Toko');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(String $id)
    {
        DB::beginTransaction();
        $toko = Toko::findOrFail($id);
        try {
            $toko->delete();
        DB::commit();

        return redirect()->route('master.toko.index')->with('success', 'Berhasil menghapus Data Toko');
        } catch (\Throwable $th) {
        DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Data Toko' . $th->getMessage());
        }
    }
}
