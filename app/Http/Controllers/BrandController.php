<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    public function index()
    {
        $brand = Brand::with('jenis')
                    ->orderBy('id', 'desc')
                    ->get();
        // $jenis = JenisBarang::all();
        return view('master.brand.index', compact('brand'));
    }

    public function create()
    {
        $jenis = JenisBarang::all();
        return view('master.brand.create', compact('jenis'),[
            'jenis' => JenisBarang::all()->pluck('id', 'nama_jenis_barang'),
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
        // dd($request->all());
        DB::beginTransaction();
        try{
            $validatedData = $request->validate([
                'nama_brand' => 'required|string|max:255',
            ],[
                'nama_brand.required' => 'Nama Brand tidak boleh kosong.',
            ]);

            Brand::create([
                'nama_brand' => $request->nama_brand,
            ]);

            DB::commit();

            return redirect()->route('master.brand.index')->with('success', 'Data berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit(string $id)
    {

        $brand = Brand::with('jenis')->findOrFail($id);

        $jenis = JenisBarang::all();
        return view('master.brand.edit', compact('brand', 'jenis'));
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        $validatedData = $request->validate([
            'nama_brand' => 'required|string|max:255',
        ],[
            'nama_brand.required' => 'Nama Brand tidak boleh kosong.',
        ]);

        $brand = Brand::findOrFail($id);

        $brand->update([
            'nama_brand' => $request->nama_brand,
        ]);

        DB::commit();

        return redirect()->route('master.brand.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        try {
            $brand = Brand::findOrFail($id);
            $brand->delete();

            DB::commit();

            return redirect()->route('master.brand.index')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('master.brand.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
