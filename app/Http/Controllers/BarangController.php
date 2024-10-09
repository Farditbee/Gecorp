<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Brand;
use App\Models\DetailPembelianBarang;
use App\Models\JenisBarang;
use App\Models\PembelianBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('brand', 'jenis')
                        ->orderBy('id', 'desc')
                        ->get();
        return view('master.barang.index', compact('barang'));
    }

    public function create()
    {
        $jenis = JenisBarang::all();
        $brand = Brand::all();
        // Mengirim data ke view
        return view('master.barang.create', compact('brand', 'jenis'), [
            'brand' => Brand::all()->pluck('nama_brand','id'),
            'jenis' => JenisBarang::all()->pluck('nama_jenis_barang', 'id'),
        ]);
    }

            public function getBrandsByJenis(Request $request)
        {
            // Validasi bahwa id_jenis_barang dikirim melalui AJAX
            $request->validate([
                'id_jenis_barang' => 'required|exists:jenis_barang,id'
            ]);

            // Ambil semua Brand yang memiliki id_jenis_barang sesuai dengan yang dipilih
            $brands = Brand::where('id_jenis_barang', $request->id_jenis_barang)->get();

            // Kembalikan data dalam bentuk JSON
            return response()->json($brands);
        }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $validatedData = $request->validate([
                'id_jenis_barang' => 'required|string|max:255',
                'id_brand_barang' => 'required|string|max:255',
                'nama_barang' => 'required|string|max:255',
            ],[
                'id_jenis_barang.required' => 'Jenis Barang tidak boleh kosong.',
                'id_brand_barang.required' => 'Brand Barang tidak boleh kosong.',
                'nama_barang.required' => 'Nama Barang tidak boleh kosong.',
            ]);

            Barang::create([
                'id_jenis_barang' => $request->id_jenis_barang,
                'id_brand_barang' => $request->id_brand_barang,
                'nama_barang' => $request->nama_barang,
            ]);

            DB::commit();

            return redirect()->route('master.barang.index')->with('success', 'Data Barang berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $barang = Barang::with('brand', 'jenis')->findOrFail($id);
        $brand = Brand::all();
        $jenis = JenisBarang::all();
        return view('master.barang.edit', compact('barang', 'brand', 'jenis'));
    }

    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);
        try {
            $barang->update([
                'id_jenis_barang' => $request->id_jenis_barang,
                'id_brand_barang' => $request->id_brand_barang,
                'nama_barang' => $request->nama_barang,
            ]);
            return redirect()->route('master.barang.index')->with('success', 'Sukses Mengubah Data User');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        $barang = Barang::findOrFail($id);
        try {
            $barang->delete();
        DB::commit();
        return redirect()->route('master.barang.index')->with('success', 'Sukses menghapus Data Barang');
        } catch (\Throwable $th) {
        DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Data Barang' . $th->getMessage());
        }
    }
}
